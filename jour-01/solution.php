<?php
// -- inputs

/** @var string $message The ciphered message **/
$message = <<<TXT
qjx uwjufwfynkx uwjssjsy iz wjyfwi qjx jqkjx xtsy ijgtwijx jy qf qtlnxynvzj iz ywfnsjfz jxy js ufssj.
qj ujwj stjq hmjwhmj zs tz zsj ija hfufgqj ij qzn uwjyjw rfns ktwyj.
qjx qzynsx xtsy itzjx fajh qjx otzjyx rtnsx fajh qj htij.
fajh hjyyj wjxtqzynts yz fx uwtzaj yf afqjzw jy jrgfwvzj ifsx hjyyj fajsyzwj !!!
TXT;

/** @var int $transferCount The amount of letter to take back to have the original message **/
$transferCount = -5;

// -- helpers

/**
 * Applies a transformation to a character to decipher the message.
 * 
 * @param string $char the character to transform.
 * @param int $shift the amount of character order to shift
 * @return string the transformated character
 **/
function applyTransform(string $char, int $shift): string
{
    // declaring boundaries for a <-> z chars
    $lowerCharBoundary = ord("a");
    $higherCharBoundary = ord("z");

    // taking order (eg binary representation) from the char gien
    $order = ord($char);

    // applying transformation
    $order += $shift;

    // wrap around the boundaries
    if ($order < $lowerCharBoundary) {
        $order += 26;
    } elseif ($order > $higherCharBoundary) {
        $order -= 26;
    }

    return chr($order);
}

/**
 * Deciphers the message
 * 
 * @param string $message the ciphered message
 * @return string the deciphered message
 **/
function decipher(string $message): string
{
    global $transferCount;
    $decipher = "";

    $lowerCharBoundary = ord("a");
    $higherCharBoundary = ord("z");

    $loopThreshold = strlen($message);

    for ($i = 0; $i < $loopThreshold; $i++) {
        $char = $message[$i];
        $order = ord($char);

        if ($order >= $lowerCharBoundary && $order <= $higherCharBoundary) {
            $decipher .= applyTransform($char, $transferCount);
        } else {
            $decipher .= $char;
        }
    }

    return $decipher;
}

// -- main
$decipher = decipher($message);
echo "Message chiffré :\n";
echo "$message\n";
echo "\n";
echo "Message en clair :\n";
echo "$decipher";
