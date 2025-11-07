<style>
    :root {
        @if(getOption('app_color_design_type',DEFAULT_COLOR) == DEFAULT_COLOR)

        --brand-primary: #000064;
        --sidebar-bg:  #000064;
        --button: #f5dc3c;
        --red: #fb5421;
        --red-rgb: 251, 84, 33;
        --button-primary-color: #000064;
        --title-text: #121d35;
        @else

        --brand-primary: {{ getOption('primary_color','#000064') }};
        --red: {{ getOption('secondary_color','#fb5421') }};
        --red-rgb: {{ getOption('secondary_color_rgb','251, 84, 33') }};
        --sidebar-bg: {{ getOption('sidebar_color',' #000064') }};
        --button-primary-color: {{ getOption('button_secondary_color','#000064') }} ;
        --button: {{ getOption('button_primary_color','#000064') }};
        --title-text: {{ getOption('title_text_color','#121d35') }};
        @endif

       --white: #ffffff;
        --tertiary: #6afcef;
        --white-10: rgba(255, 255, 255, 0.1);
        --white-20: rgba(255, 255, 255, 0.2);
        --white-90: rgba(255, 255, 255, 0.9);
        --black: #000000;
        --secondary: #f8f8f8;
        --button-alt: #f5c000;
        --title-text-75: rgba(18, 29, 53, 0.75);
        --para-text: #636370;
        --stroke: #efefef;
        --stroke-2: #dddddd;
        --stroke-3: #ead4d4;
        --primary-light: #f3f0fb;
        --red-light: #ffe5e5;
        --green: #14d971;
        --btn-secondary: #d8d8d8;
        --sidebar-divider: #6f40e0;
        --neutrals-100: #e8ebed;
        --success-100: #dcfce7;
        --success-500: #22c55e;
        --error-100: #fee2e2;
        --error-500: #ef4444;
        --warning-50: #fefce8;
        --neutrals-100: #e8ebed;
        --hold-text: #fe5dfe;
        --hold-bg: #fcdcfb;
        --refunded-text: #ef7744;
        --refunded-bg: #fef3e2;
        --gray-40: #9ca3af;
        --gray-50: #6b7280;
        --gray-90: #101827;
        --dark-90: #111317;
        --blue: #07076d;
        --blue-alt: #06068d;
        --form-label-text: #151515;
        --form-border: #e8e8e8;
        --secondary-bg: #fff0f0;
        --scroll-track: #efefef;
        --scroll-thumb: #dadada;
    }
</style>
