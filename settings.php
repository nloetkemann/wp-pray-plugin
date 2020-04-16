<?php


function getOptionInput($key, $heading, $value)
{
    return "
            <th scope='row'>
                <label>
                    $heading
                </label>
            </th>
            <td>
                <input class='regular-text' type='text' name='value' value='$value'>
            </td>
            <input hidden name='key' value='$key'>
        ";
}

function getSettings()
{
    $emailKey = 'prayer-email-list';
    $prayCounterKey = 'prayer-counter';
    if (!get_option($emailKey)) {
        var_dump("hallo");
        add_option($emailKey, '', '', 'yes');
    }
    if (!get_option($prayCounterKey)) {
        add_option($prayCounterKey, 0, '', 'yes');
    }

    $emailList = get_option($emailKey);
    $prayerCounter = get_option($prayCounterKey);

    $emailForm = getOptionInput('prayer-email-list', 'E-Mail Empfänger (mit Kommatas trennen)', $emailList);
    echo "
        <header>
            <h1>Pray Plugin</h1>
            <p>Die Einstellungen für das Pray Plugin</p>
        </header>
        <div id='pray-settings'>
            <form method='post'>
                <table class='form-table' role='presentation'>
                    <input hidden name='type' value='resetCounter'>
                    <tbody>
                        <tr>
                            <th scope='row'>Bisher gab es $prayerCounter Gebetsanliegen</th>
                            <td><button class='button button-secondary' type='submit'>Zähler zurücksetzen</button></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <form method='post'>
                <table class='form-table' role='presentation'>
                    <tbody>
                        <tr>$emailForm</tr>
                    </tbody>
                </table>
                <input hidden name='type' value='saveOption'>
                <button class='button button-primary' id='submit' type='submit'>Speichern</button>
            </form>
        </div>
        <footer style='margin-top: 4em' id='pray-footer'>Wir wünschen euch viel Erfolg und Gottes Segen mit dem Plugin.</footer>
    ";
}

function saveOption($data, $keyname, $valuename)
{
    $key = $data[$keyname];
    $value = $data[$valuename];
    update_option($key, $value, '', 'yes');
}

function resetCounter()
{
    update_option('prayer-counter', 0, 'yes');
}


if (isset($_POST['type']) && $_POST['type'] === 'saveOption') {
    saveOption($_POST, 'key', 'value');
} else if (isset($_POST['type']) && $_POST['type'] === 'resetCounter') {
    resetCounter();
}



