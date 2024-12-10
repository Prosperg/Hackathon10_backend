<?php

namespace App\Services;

use Twilio\Rest\Client;
use Exception;

class SmsService
{
    protected $client;
    protected $fromNumber;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $this->fromNumber = config('services.twilio.phone_number');
    }

    /**
     * Envoie un SMS
     * 
     * @param string $to Numéro de téléphone du destinataire
     * @param string $message Contenu du message
     * @return array
     */
    public function send($to, $message)
    {
        try {
            // Formater le numéro de téléphone
            $to = $this->formatPhoneNumber($to);

            // Envoyer le SMS
            $message = $this->client->messages->create(
                $to,
                [
                    'from' => $this->fromNumber,
                    'body' => $message
                ]
            );

            return [
                'success' => true,
                'message' => 'SMS envoyé avec succès',
                'data' => [
                    'message_id' => $message->sid,
                    'to' => $to,
                    'status' => $message->status
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du SMS',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Formate le numéro de téléphone pour Twilio
     * 
     * @param string $number
     * @return string
     */
    protected function formatPhoneNumber($number)
    {
        // Supprimer tous les caractères non numériques
        $number = preg_replace('/[^0-9]/', '', $number);

        // Si le numéro ne commence pas par +, ajouter le code pays (+237 pour le Cameroun)
        if (!str_starts_with($number, '+')) {
            if (str_starts_with($number, '229')) {
                $number = '+' . $number;
            } else {
                $number = '+229' . $number;
            }
        }

        return $number;
    }

    /**
     * Envoie un SMS de confirmation de ticket
     * 
     * @param string $to
     * @param array $ticketData
     * @return array
     */
    public function sendTicketConfirmation($to, $ticketData)
    {
        $message = "✅ Ticket de stationnement\n\n"
                . "Code: {$ticketData['ticket_code']}\n"
                . "Catégorie: {$ticketData['category']}\n"
                . "Prix: {$ticketData['price']} FCFA\n"
                . "Durée: {$ticketData['duration']} heures\n"
                . "Plaque: {$ticketData['plate_number']}\n\n"
                . "Merci de votre confiance !";

        return $this->send($to, $message);
    }

    /**
     * Envoie un SMS de rappel avant expiration
     * 
     * @param string $to
     * @param array $ticketData
     * @return array
     */
    public function sendExpirationReminder($to, $ticketData)
    {
        $message = "⚠️ Rappel\n\n"
                . "Votre ticket de stationnement expire dans 1 heure.\n"
                . "Code: {$ticketData['ticket_code']}\n"
                . "Plaque: {$ticketData['plate_number']}\n\n"
                . "Veuillez récupérer votre moto ou renouveler votre ticket.";

        return $this->send($to, $message);
    }

    /**
     * Envoie un SMS de confirmation de paiement
     * 
     * @param string $to
     * @param array $paymentData
     * @return array
     */
    public function sendPaymentConfirmation($to, $paymentData)
    {
        $message = "💰 Paiement confirmé\n\n"
                . "Montant: {$paymentData['amount']} FCFA\n"
                . "Code ticket: {$paymentData['ticket_code']}\n"
                . "Date: {$paymentData['date']}\n\n"
                . "Merci de votre paiement !";

        return $this->send($to, $message);
    }
} 