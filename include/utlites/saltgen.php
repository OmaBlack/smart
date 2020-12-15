<?
function createSalt()
{
    $text = md5(uniqid(rand(), TRUE));
    return substr($text, 0, 3);
}
?>