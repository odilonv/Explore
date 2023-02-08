<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\HTTP\Session;

class MessageFlash
{

    // Les messages sont enregistré en session associé à la clé suivante
    private static string $cleFlash = "_messagesFlash";

    // $type parmi "success", "info", "warning" ou "danger"
    public static function ajouter(string $type, string $message): void
    {
        $session = Session::getInstance();

        $messagesFlash = [];
        if ($session->existeCle(MessageFlash::$cleFlash))
            $messagesFlash = $session->lire(MessageFlash::$cleFlash);

        $messagesFlash[$type][] = $message;
        $session->enregistrer(MessageFlash::$cleFlash, $messagesFlash);
    }

    public static function contientMessage(string $type): bool
    {
        $session = Session::getInstance();
        return $session->existeCle(MessageFlash::$cleFlash) &&
            array_key_exists($type, $session->lire(MessageFlash::$cleFlash))  &&
            !empty($session->lire(MessageFlash::$cleFlash)[$type]);
    }

    // Attention : la lecture doit détruire le message
    public static function lireMessages(string $type): array
    {
        $session = Session::getInstance();
        if (!MessageFlash::contientMessage($type))
            return [];

        $messagesFlash = $session->lire(MessageFlash::$cleFlash);
        $messages = $messagesFlash[$type];
        unset($messagesFlash[$type]);
        $session->enregistrer(MessageFlash::$cleFlash, $messagesFlash);

        return $messages;
    }

    public static function lireTousMessages() : array
    {
        $tousMessages = [];
        foreach(["success", "info", "warning", "danger"] as $type) {
            $tousMessages[$type] = MessageFlash::lireMessages($type);
        }
        return $tousMessages;
    }

}