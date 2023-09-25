<?php
    class ControllerTestmail extends Controller{
        public function index(){

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
            
            $mail->setTo('tsuyoshikoh@gmail.com');
            $mail->setFrom($this->config->get('config_mail_smtp_username'));
            $mail->setSender('Test Sender');
            $mail->setSubject('This is a test mail');
            $mail->setText('Here\'s a test content');
            $mail->send();
        }
    }