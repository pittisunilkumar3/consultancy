<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GatewayRequest;
use App\Models\Bank;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GatewayController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Gateway');
        $data['activeGateway'] = 'active';
        $data['activeSetting'] = 'active';
        $data['gateways'] = Gateway::all();
        return view('admin.setting.gateway.index', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Gateway');
        $data['pageTitle'] = __('Edit Gateway');
        $data['activeGateway'] = 'active';
        $data['activeSetting'] = 'active';
        $data['gateway'] = Gateway::findOrFail(decodeId($id));
        $data['gatewayCurrencies'] = GatewayCurrency::where('gateway_id', $data['gateway']->id)->get();
        $data['gatewaySettings'] = json_decode(gatewaySettings(), true)[$data['gateway']->slug] ?? [];
        $data['gatewayBanks'] = Bank::all();
        return view('admin.setting.gateway.edit', $data);
    }

    public function store(GatewayRequest $request)
    {
        DB::beginTransaction();
        try {
            $gateway = Gateway::findOrFail(decodeId($request->id));
            if ($gateway->slug == 'bank') {
                $bankIds = [];
                foreach ($request->bank['name'] as $i => $name) {
                    $bank = Bank::updateOrCreate(
                        ['id' => $request->bank['id'][$i] ?? null],
                        ['gateway_id' => $gateway->id, 'name' => $name, 'details' => $request->bank['details'][$i], 'status' => ACTIVE]
                    );
                    $bankIds[] = $bank->id;
                }
                Bank::whereNotIn('id', $bankIds)->delete();
            } else {
                $gateway->mode = $request->mode == GATEWAY_MODE_LIVE ? GATEWAY_MODE_LIVE : GATEWAY_MODE_SANDBOX;
                $gateway->url = $request->url;
                $gateway->key = $request->key;
                $gateway->secret = $request->secret;
            }
            $gateway->status = $request->status == STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_PENDING;
            $gateway->save();

            $gatewayCurrencyIds = [];
            foreach ($request->currency as $key => $currency) {
                $gatewayCurrency = GatewayCurrency::updateOrCreate(
                    ['id' => $request->currency_id[$key] ?? null],
                    ['gateway_id' => $gateway->id, 'currency' => $currency, 'conversion_rate' => $request->conversion_rate[$key]]
                );
                $gatewayCurrencyIds[] = $gatewayCurrency->id;
            }
            GatewayCurrency::whereNotIn('id', $gatewayCurrencyIds)->where('gateway_id', $gateway->id)->delete();

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function getInfo(Request $request)
    {
        $gateway = Gateway::findOrFail($request->id);
        $data = [
            'gateway' => $gateway,
            'banks' => $gateway->slug == 'bank' ? Bank::all() : [],
            'currencies' => GatewayCurrency::where('gateway_id', $gateway->id)->get()
        ];
        return $this->success($data);
    }

    public function getCurrencyByGateway(Request $request)
    {
        $currencies = GatewayCurrency::where('gateway_id', $request->id)->get();
        return $this->success($currencies);
    }

    public function syncs()
    {
        syncMissingGateway();
        return redirect()->back()->with('success', 'Gateways synced successfully!');
    }
}
