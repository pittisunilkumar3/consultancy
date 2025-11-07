<div class="row rg-24">
    @foreach($documents as $document)
        <div class="col-xl-3 col-md-4 col-sm-6">
            <a target="_blank" href="{{getFileUrl($document->file)}}" class="document-link">
                <div
                    class="bd-c-stroke bd-one d-flex bd-ra-5 flex-column p-15 rg-20 bg-secondary ordersDoc">
                    <div class="position-relative overflow-hidden d-flex img justify-content-center align-items-center bd-ra-5 bg-white ordersDoc-img">
                        @if(in_array($document->filemanager->extension, ['jpg', 'png', 'jpeg', 'webp', 'JPG', 'PNG', 'JPEG', 'WEBP']))
                            <div class="img"><img src="{{ getFileUrl($document->file) }}" alt="{{$document->name}}"></div>
                        @else
                            <div class="img fileIcon"><img src="{{ asset('assets/images/document-icon.png') }}" alt=""></div>
                        @endif
                        @if(auth()->user()->role != USER_ROLE_STUDENT)
                            <div class="position-absolute top-10 right-10 d-flex g-10">
                                <button
                                    class="edit-btn d-flex justify-content-center align-items-center bd-one bd-c-stroke rounded-circle bg-white d-flex flex-shrink-0 w-30 h-30"
                                    onclick="getEditModal('{{route('admin.service_orders.documents.edit', [$document->id])}}', '#edit-document-modal')">
                                    @include('partials.icons.edit')
                                </button>

                                <button
                                    class="delete-btn d-flex justify-content-center align-items-center bd-one bd-c-stroke rounded-circle bg-white d-flex flex-shrink-0 w-30 h-30"
                                    onclick="deleteItem('{{ route('admin.service_orders.documents.delete', [$document->id]) }}', null, '{{ route('admin.service_orders.documents', encodeId($serviceOrder->id)) }}')">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex flex-column rg-10">
                        <div class="d-flex justify-content-between align-items-start">
                            <h4 class="fs-16 fw-400 lh-17 text-brand-primary text-title-text">{{ $document->name }}</h4>
                            <span class="fs-12 fw-400 lh-15 text-para-text">({{ $document->filemanager->extension }})</span>
                        </div>
                        <p class="fs-12 fw-400 lh-15 text-para-text">
                            <span>{{ getFileSize($document->filemanager->size) }}</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
<!-- Pagination -->
{{ $documents->links('layouts.partial.common_pagination_with_count') }}
