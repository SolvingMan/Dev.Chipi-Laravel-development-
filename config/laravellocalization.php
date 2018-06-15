<?php

return [
    'useAcceptLanguageHeader' => false, // мы используем наше значение конфига /config/app.php: locale=>en)
    'useSessionLocale' => false, // мы берём локаль из адреса
    'useCookieLocale' => false, // - отключаем чтобы не перебивало, когда меняем локаль (пояснение в конце)
];