<?php

namespace MineDoc\HomeBundle\Service;

use PHPsend;

class MinecraftCommands {

    public function sendCommand($command, $session)
    {
        if ($session->get('logged') > 0)
        {
            $con = new \PHPsend();
            $con->PHPconnect("localhost","3fdfa535dedb1556","6530");
            $con->PHPcommand($command);
            $con->PHPcommand("say ".$session->get('name')." vient de faire cette commande: [".$command."] !");
            $con->PHPdisconnect();
        }
        else {
            echo "<div class='warning throw'>Vous n'avez pas la permission</div>";
        }
    }

    public function sendCommandCrea($command, $session)
    {
        if ($session->get('logged') > 0) {
            $con = new \PHPsend();
            $con->PHPSonnect("localhost", "3fdfa535dedb1557", "6531");
            $con->PHPScommand($command);
            $con->PHPScommand("say " . $session->get('name') . " vient de faire cette commande: [" . $command . "] !");
            $con->PHPSdisconnect();
        } else {
            echo "<div class='warning throw'>Vous n'avez pas la permission</div>";
        }
    }

    public function sendSilentCommand($command, $session)
    {
        if ($session->get('logged') > 0)
        {
            $con = new \PHPsend();
            $con->PHPSconnect("localhost","3fdfa535dedb1556","6530");
            $con->PHPScommand($command);
            $con->PHPSdisconnect();
        }
        else {
            echo "<div class='warning throw'>Vous n'avez pas la permission</div>";
        }
    }

    public function sendSilentCommandCrea($command, $session)
    {
        if ($session->get('logged') > 0) {
            $con = new \PHPsend();
            $con->PHPSconnect("localhost", "3fdfa535dedb1557", "6531");
            $con->PHPScommand($command);
            $con->PHPSdisconnect();
        } else {
            echo "<div class='warning throw'>Vous n'avez pas la permission</div>";
        }
    }

    public function sendCommandBuy($obj, $nbr, $command, $session)
    {
        if ($session->get('logged') > 0)
        {
            $con = new \PHPsend();
            $con->PHPconnect("localhost","3fdfa535dedb1556","6530");
            $con->PHPcommand($command);
            $con->PHPcommand("say ".$session->get('name')." a achete: ".$nbr." ".$obj." !");
            $con->PHPdisconnect();
        }
        else
        {
            echo "<div class='warning throw'>Vous n'avez pas la permission</div>";
        }
    }

    public function chatParse($str)
    {
        $patterns = array();
        $patterns[0] = '/\s\s+/';
        $patterns[1] = '@(https?://([-\w\.]+)+(:\d+)?((/[\w/_\.%\-+~]*)?(\?\S+)?)?)@';
        $patterns[2] = '#mouaha(ha)*#i';
        $patterns[3] = '#8\)#';
        $patterns[4] = '#:o#i';
        $patterns[5] = '#:-?\(#';
        $patterns[6] = '#:(\,|\'|\`)\(#';
        $patterns[7] = '#:-?s#i';
        $patterns[8] = '#:-?D#';
        $patterns[9] = '#;-?(\)|D)#';
        $patterns[10] = '#:-?\|#';
        $patterns[11] = '#:-?\)#';
        $replacements = array();
        $replacements[0] = ' ';
        $replacements[1] = '<a href="$1" target="blank">$1</a>';
        $replacements[2] = '<img src="/bundles/minedochome/images/chat/smiley-evil.png"/>';
        $replacements[3] = '<img src="/bundles/minedochome/images/chat/smiley-cool.png"/>';
        $replacements[4] = '<img src="/bundles/minedochome/images/chat/smiley-eek.png"/>';
        $replacements[5] = '<img src="/bundles/minedochome/images/chat/smiley-bad.png"/>';
        $replacements[6] = '<img src="/bundles/minedochome/images/chat/smiley-cry.png"/>';
        $replacements[7] = '<img src="/bundles/minedochome/images/chat/smiley-confuse.png"/>';
        $replacements[8] = '<img src="/bundles/minedochome/images/chat/smiley-grin.png"/>';
        $replacements[9] = '<img src="/bundles/minedochome/images/chat/smiley-wink.png"/>';
        $replacements[10] = '<img src="/bundles/minedochome/images/chat/smiley-neutral.png"/>';
        $replacements[11] = '<img src="/bundles/minedochome/images/chat/smiley.png"/>';
        return preg_replace($patterns, $replacements, $str);
    }
}