<?php

$prayerName = "";
$prayerEmail = "";
$content = "";

function sendEmail()
{
    $prayerContent = $GLOBALS['$content'];
    $prayerName = $GLOBALS['$prayerName'];
    $prayerEmail = $GLOBALS['$prayerEmail'];
    $prayerTelNumber = $GLOBALS['$prayerTel'];

    $contentPrayerName = "<b>Name:</b> " . ($prayerName != "" ? $prayerName : "Es gibt keinen Namen");
    $contentPrayerTel = "<b>Telefonnummer:</b> " . ($prayerTelNumber != "" ? " " . $prayerTelNumber : "Keine Telefonnummer angegeben.");
    $contentPrayerEmail = "<b>E-Mail:</b> " . ($prayerEmail != "" ? $prayerEmail : "Keine E-Mail Addresse angegeben");
    $headers[] = $prayerEmail != "" ? 'Reply-To: ' . $prayerName . '<' . $prayerEmail . '>' : null;
    $headers[] = 'Content-type: text/html';

    $content = "
       <b>Gebetsanliegen:</b>
       
       $prayerContent
       
       
       $contentPrayerName
       $contentPrayerEmail
       $contentPrayerTel
       
       Mit freundlichen Grüßen,
       Dein Gebetsplugin
    ";
    $allReceiver = explode(',', get_option('prayer-email-list'));

    foreach ($allReceiver as $receiver) {
        if ($receiver !== "") {
            if (!wp_mail(trim($receiver), 'Ein neues Gebetsanliegen kam gerade rein', nl2br($content), $headers)) {
                error_log('konnte nicht gesendet werden');
            }
        }
    }
    $counter = get_option('prayer-counter');
    if ($counter !== false) {
        update_option('prayer-counter', ++$counter, 'yes');
    } else {
        add_option('prayer-counter', 0, '', 'yes');
    }

}

if (isset($_POST['submit'])) {
    $GLOBALS['$prayerName'] = $_POST['prayer-name'];
    $GLOBALS['$prayerEmail'] = $_POST['email-address'];
    $GLOBALS['$prayerTel'] = $_POST['tel-number'];
    $GLOBALS['$content'] = $_POST['prayer-request'];
    add_action('plugins_loaded', 'sendEmail');
}


function showPlugin()
{
    $imagePath = plugins_url('praying-hands-solid.svg', __FILE__);
    return "<div>
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css'>
            <script type='application/javascript'>
                let step = -1;
                let isOpen = false;
                
                $(document).ready(function() {
                    let textarea = $('#prayer-request')
                    textarea.on('keypress', autoResize);
                });
                
                function back() {
                    if (step === 0){ 
                        $('#widget').hide();
                        isOpen = false;
                        step--;
                    } else if (step === 1) {
                        $('#step0').show();
                        $('#step1').hide();
                        step--;
                    } else if (step === 2) {
                        $('#step1').show();
                        $('#step2').hide();
                        step--;
                    } else if (step === 3) {
                        $('#step2').show();
                        $('#step3').hide();
                        step++;
                    } else if (step === 4) {
                        if ($('#plugin-accept-gdpr').is(':checked')) {
                            $('#step3').show();
                            step--;
                        } else {
                            $('#step2').show();
                            step -= 2;
                        }
                        $('#plugin-submit-button').hide();
                        $('#plugin-next-button').show();
                        $('#step4').hide();
                        
                    }
                }
                
                function next() {
                    if (step === 0){ // start insert name
                        $('#step1').show();
                        $('#step0').hide();
                        step++;
                    } else if (step === 1) { // insert prayer request
                        $('#step2').show();
                        $('#step1').hide();
                        step++;
                    } else if (step === 2) { // agree or disagree to gdpr
                        if ($('#plugin-accept-gdpr').is(':checked')){
                           $('#step3').show(); 
                           step++;
                           
                        } else {
                            $('#step4').show();
                            $('#plugin-submit-button').show();
                            $('#plugin-next-button').hide();
                            step+=2;
                        }
                        $('#step2').hide();
                    } else if (step === 3) { // ask for email
                        $('#step4').show();
                        $('#plugin-submit-button').show();
                        $('#plugin-next-button').hide();
                        $('#step3').hide();
                        step++;
                    }
                }
                
                function close_widget() {
                    $('#step0').hide(); 
                    $('#background-click').hide();
                    $('#step1').hide();                        
                    $('#step2').hide();                        
                    $('#step3').hide();                        
                    $('#step4').hide();
                    $('#widget').hide();
                    step = -1;
                    isOpen = false;
                }
                
                function open_widget() {
                    step = 0;
                    $('#step0').show();                        
                    $('#widget').show();
                    $('#background-click').show();
                    isOpen = true;
                }
                
                function change_widget_state() {
                    if (!isOpen) {
                        open_widget()
                    } else {
                        close_widget()
                    }
                }
                
                function autoResize() {
                    let textarea = $('#prayer-request')
                    setTimeout(function() {
                        textarea.css({height: 'auto'});
                        textarea.css({height: textarea.prop('scrollHeight')});
                    },0)
                }
                
                function validate() {
                    next();
                    return step === 4;
                }
            </script>
            <div hidden id='background-click' onclick='close_widget()'></div>
            <div id='plugin-open-widget'>
                  <button type='button' id='plugin-open-widget-button' onclick='change_widget_state()'>
                      <img id='plugin-open-icon' src='$imagePath' alt='Open'>
                  </button>
            </div>
            <div hidden id='widget'>
                <form method='post' onsubmit='return validate()'>
                     <div id='step0'>
                        <h4 class='plugin-header'>Hi, wie heißt du?</h4>
                        <div class='plugin-content'>
                            <input autofocus type='text' placeholder='Dein Name' name='prayer-name' id='prayer-name'>
                        </div>             
                    </div>
                    <div id='step1' hidden>
                        <h4 class='plugin-header'>Welches Gebetsanliegen hast du?</h4>
                        <div class='plugin-content'>
                            <textarea autofocus placeholder='Deine Anliegen' name='prayer-request' id='prayer-request'></textarea>
                        </div>
                    </div>
                    <div id='step2' hidden>
                        <h4 class='plugin-header'>Möchtest du mit uns noch weiteren Kontak haben?</h4>
                        <div class='plugin-content'>
                            <p>
                                Indem sie das bestätigen, stimmen sie auch unseren 
                                <a href='https://cg-rahden.de/datenschutz/'>Datenschutzbestimmungen</a> zu.
                                Sie können auch fortfahren ohne weiteren Kontakt, wenn sie das wünschen.
                            </p>                            
                            <input name='accept-gdpr' id='plugin-accept-gdpr' type='checkbox' title='Ich aktzeptiere die Datenschutzbestimmung.' style='float: left'>
                            <p style='overflow:hidden;padding: 0 0 0 5px;'>Ich bestätige</p>     
                        </div>
                    </div> 
                    <div id='step3' hidden>
                        <h4 class='plugin-header'>Dann brauchen wir eine Kontaktmöglichkeit.</h4>
                        <div class='plugin-content'>
                            <input name='email-address' type='email' placeholder='Deine Emailaddresse'>
                            <input name='tel-number' type='tel' placeholder='Deine Telefon Nummer'>
                        </div>
                    </div>
                    <div id='step4' hidden>
                        <h4 class='plugin-header'>Abschicken?</h4>
                    </div>     
                    <button type='button' id='plugin-back-button' class='plugin-button' onclick='back()'>Zurück</button>    
                    <button type='submit' id='plugin-submit-button' class='plugin-button' name='submit'>Abschicken</button>
                    <button type='button' id='plugin-next-button' class='plugin-button' onclick='next()'>Weiter</button>             
                </form>
            </div>
        </div>";
}
