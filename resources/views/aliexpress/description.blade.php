@if($language=="he")
    <div style="color:#ce0900">@lang('general.translate_description_into_language'):</div>
    <!--<img src="img/Flag_of_Israel.svg.png" class="israelflag">-->
    <div class="translate_control" lang="iw"></div>
    <div id="google_translate_element"></div>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'iw',
                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript"

            src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
@endif
<?php echo $description; ?>