<?php
$_ERROR = array();
$_NOTICE = '';

function params($name) {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
}

function has_error() {
    global $_ERROR;
    return count($_ERROR) > 0 ? true : false;
}

function error_messages() {
    global $_ERROR;
    $count = count($_ERROR);
    if (!($count > 0)) return '';
    echo "<div class=\"error_messages\">\n";
    echo "  <p class=\"title\">{$count}個のエラーがあります。</p>";
    echo "  <ul class=\"errors\">\n";
    foreach ($_ERROR as $error) {
    echo "    <li class=\"error\">{$error}</li>\n";
    }
    echo "  </ul>\n";
    echo "</div>\n";
}

function notice_message() {
    global $_NOTICE;
    if ($_NOTICE === '') return '';
    echo "<div class=\"notice_message\">\n";
    echo "  <p>{$_NOTICE}</p>\n";
    echo "</div>\n";
}

function validate_presence_of($name) {
    global $_ERROR;
    if (!isset($_REQUEST[$name]) or
        mb_strlen($_REQUEST[$name]) == 0) {
        $_ERROR[] = i18n($name).'を入力してください。';
    }
}

function validate_length_of($name, $options = array()) {
    global $_ERROR;

    // min
    if (isset($options['min'])) {
        if ($options['min'] >= mb_strlen($_REQUEST[$name])) {
            $_ERROR[] = i18n($name).'は'.$options['min'].'文字以上にしてください。';

        }
    }

    // max
    if (isset($options['max'])) {
        if ($options['max'] < mb_strlen($_REQUEST[$name])) {
            $_ERROR[] = i18n($name).'は'.$options['max'].'文字以下にしてください。';

        }
    }
}

function validate_numericality_of($name, $options = array()) {
    global $_ERROR;

    if (!is_numeric($_REQUEST[$name])) {
        $_ERROR[] = i18n($name).'は数字にしてください。';
        return false;
    }

    if (isset($options['greater_than'])) {
        if (((int) $_REQUEST[$name]) <= $options['greater_than']) {
            $_ERROR[] = i18n($name).'は'.$options['greater_than'].'より大きい数にしてください。';
        }
    }

    if (isset($options['greater_than_or_equal_to'])) {
        if (((int) $_REQUEST[$name]) < $options['greater_than_or_equal_to']) {
            $_ERROR[] = i18n($name).'は'.$options['greater_than'].'以上にしてください。';
        }
    }

    if (isset($options['less_than'])) {
        if (((int) $_REQUEST[$name]) >= $options['less_than']) {
            $_ERROR[] = i18n($name).'は'.$options['less_than'].'より少ない数にしてください。';
        }
    }

    if (isset($options['less_than_or_equal_to'])) {
        if (((int) $_REQUEST[$name]) > $options['less_than_or_equal_to']) {
            $_ERROR[] = i18n($name).'は'.$options['less_than_or_equal_to'].'以下にしてください。';
        }
    }
}

function validate_email_of($name) {
    global $_ERROR;
    if (!preg_match('/^[a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_REQUEST[$name])) {
        $_ERROR[] = i18n($name).'を正しい書式で入力してください。';
    }
}

function nice_file_put_contents($filename, $data, $mode = 'w') {
    $fp = fopen($filename, $mode);
    fwrite($fp, $data);
    fclose($fp);
}

function fetch($file) {
    ob_start();
    include($file);
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function capture($code) {
    ob_start();
    eval($code);
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function redirect($url) {
    header('Location: '.$url);
}

function redirect_by_html($url) {
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
  \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"ja\" lang=\"ja\">
  <head>
    <meta http-equiv=\"refresh\" content=\"0;URL={$url}\" />
  </head>
  <body>
  </body>
</html>";
    exit(0);
}

function number_select($name, $max = 20) {
    $numbers = array();
    for ($i = 0; $i <= $max; $i++)
        $numbers[$i] = $i;
    return select_field($name, $numbers);
}

function prefecture_select_field($name) {
    $prefs = array(
        "北海道",
        "青森県",
        "岩手県",
        "宮城県",
        "秋田県",
        "山形県",
        "福島県",
        "茨城県",
        "栃木県",
        "群馬県",
        "埼玉県",
        "千葉県",
        "東京都",
        "神奈川県",
        "新潟県",
        "富山県",
        "石川県",
        "福井県",
        "山梨県",
        "長野県",
        "岐阜県",
        "静岡県",
        "愛知県",
        "三重県",
        "滋賀県",
        "京都府",
        "大阪府",
        "兵庫県",
        "奈良県",
        "和歌山県",
        "鳥取県",
        "島根県",
        "岡山県",
        "広島県",
        "山口県",
        "徳島県",
        "香川県",
        "愛媛県",
        "高知県",
        "福岡県",
        "佐賀県",
        "長崎県",
        "熊本県",
        "大分県",
        "宮崎県",
        "鹿児島県",
        "沖縄県"
    );
    $hash = array();
    foreach ($prefs as $pref)
        $hash[$pref] = $pref;
    return select_field($name, $hash);
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function hbr($str) {
    return nl2br(h($str));
}

function fools_select_field($name, $values = array(), $options = array()) {
    $default = "選択してください";
    array_unshift($values, $default);
    $hash = array();
    foreach ($values as $value)
        $hash[$value] = $value;
    $hash[$default] = "";
    return select_field($name, $hash);
}

function text_field($name, $options = array()) {
    $v = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
    if (!isset($options['value'])) $options['value'] = $v;
    $p = build_attributes($options);
    return "<input type=\"text\" name=\"{$name}\"{$p} />";
}

function hidden_field($name, $options = array()) {
    $v = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
    if (!isset($options['value'])) $options['value'] = $v;
    $p = build_attributes($options);
    return "<input type=\"hidden\" name=\"{$name}\"{$p} />";
}

function text_area($name, $options = array()) {
    $value = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
    $p = build_attributes($options);
    return "<textarea name=\"{$name}\"{$p}>{$value}</textarea>";
}

function build_attributes($options = array()) {
    $attrs = array();
    foreach ($options as $key => $value) {
        $attrs[] = "{$key}=\"{$value}\"";
    }
    return (count($attrs) > 0) ? ' '.join(' ', $attrs) : '';
}

function select_field($name, $values = array(), $options = array()) {
    $v = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
    $p = build_attributes($options);

    $html = "<select name=\"{$name}\"{$p}>";
    foreach ($values as $key => $value) {
        $select = ($v == $key) ? ' selected="selected"' : '';
        $html .= "<option value=\"$value\"$select>$key</option>\n";
    }
    $html .= '</select>';
    return $html;
}

function radio_button($name, $value, $options = array()) {
    $v = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
    $p = build_attributes($options);
    $checked = $v === $value ? ' checked="checked"' : '';
    return "<input type=\"radio\" name=\"{$name}\" value=\"{$value}\"{$p}{$checked} />";
}

function html_options($options, $selected = null) {
    $html = '';
    foreach ($options as $key => $value) {
        $select = '';
        if ($selected === $key) {
            $select = ' selected="selected"';
        }
        $html .= "<option value=\"$key\"$select>$value</option>\n";
    }
    return $html;
}
?>
