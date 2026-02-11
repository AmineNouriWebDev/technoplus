<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "Cache vidé !";
} else {
    echo "OPcache n'est pas activé";
}
?>