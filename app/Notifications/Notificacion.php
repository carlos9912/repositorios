<?php

namespace App\Notifications;

use App\Models\Persona;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Messages\NexmoMessage;

class notificacion extends Notification
{
    use Queueable;

    private $informacionNotificacion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notificacion = [])
    {
        $this->informacionNotificacion = $notificacion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['sms'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $persona = Persona::find(Auth::user()->id_persona);
        //$correo = env('MAIL_USERNAME');
        $correo = "conexioneducativa04@gmail.com";
        return (new MailMessage)->from($correo,$persona->nombreCompleto())->subject($this->informacionNotificacion['title'])
        ->markdown('layouts.mail', ['informacion' => $this->informacionNotificacion['contenido']])
        ->line($this->informacionNotificacion['contenido']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toNexmo($notifiable)
    {
        dd("hola");
        return (new NexmoMessage)
                    ->content($this->informacionNotificacion['contenido'])
                    ->from('15554443333');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->informacionNotificacion['title'],
            'contenido' => $this->informacionNotificacion['contenido'],
            'tipo_notificacion' => $this->informacionNotificacion['tipo_notificacion'],
            'color' => $this->informacionNotificacion['color'],
            'icon' => $this->informacionNotificacion['icon'],
            'route' => $this->informacionNotificacion['route'],
            'origen' => $this->informacionNotificacion['origen'],
            $this->informacionNotificacion['origen'] => $this->informacionNotificacion[$this->informacionNotificacion['origen']],
        ];
    }
}
