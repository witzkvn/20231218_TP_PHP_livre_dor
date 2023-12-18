<?php
class Message {
    const MIN_LIMIT_USERNAME = 3;
    const MIN_LIMIT_MESSAGE = 10;
    private string $username;
    private string $message;
    private DateTime $date;

    public static function fromJSON(string $json): Message {
        $data = json_decode($json, true);
        return new self($data['username'], $data['message'], new DateTime("@".$data['date']));
    }

    public function __construct(string $username, string $message, ?DateTime $date = null) {
        $this->username = $username;
        $this->message = $message;
        $this->date = $date ?? new DateTime();
    }

    public function isValid(): bool {
        return empty($this->getErrors());
    }

    public function getErrors(): array{
        $errors = [];
        if(strlen($this->username) < self::MIN_LIMIT_USERNAME) {
            $errors["username"] = "Votre nom doit contenir au moins 3 caractères";
        }
        if(strlen($this->message) < self::MIN_LIMIT_MESSAGE) {
            $errors["message"] = "Votre message doit contenir au moins 10 caractères";
        }
        return $errors;
    }

    public function toHTML(): string {
        $username = htmlentities($this->username);
        $this->date->setTimezone(new DateTimeZone('Europe/Paris'));
        $date = $this->date->format('d/m/Y à H:i');
        $message = nl2br(htmlentities($this->message));

        return <<<HTML
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">$username</h5>
                    <p class="card-text">$message</p>
                    <p class="card-text">$date</p>
                </div>
            </div>
        HTML;
    }

    public function toJSON(): string
    {
        return json_encode([
            'username' => $this->username,
            'message' => $this->message,
            'date' => $this->date->getTimestamp() 
        ]);
    }
}