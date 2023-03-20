<?php

namespace UpAssist\Neos\FrontendLogin\Service;

use Neos\Flow\Annotations as Flow;
use Neos\SwiftMailer\Message;

/**
 * @Flow\Scope("singleton")
 */
class EmailService
{

    /**
     * @var string $template
     * @Flow\InjectConfiguration (path="emailTemplates")
     */
    protected $template;

    /**
     * @param string $templateName
     * @param array $arguments
     * @return bool
     */
    public function sendEmail(string $templateName, array $arguments): bool
    {
        $mail = new Message();

        $variables = match ($templateName) {
            'passwordReset' => [
                'recipient' => $arguments['recipient']->getName()->getFirstName(),
                'sender' => $arguments['sender']->getName()->getFirstName(),
                'domain' => $arguments['domain'],
                'link' => $arguments['link'],
                'locale' => $arguments['locale'] ?? 'en'
            ],
            default => [
                'recipient' => $arguments['recipient']->getName()->getFirstName(),
                'sender' => $arguments['sender']->getName()->getFirstName(),
                'locale' => $arguments['locale'] ?? 'en'
            ],
        };

        if (isset($this->template[$templateName][$arguments['locale']])) {
            $template = $this->prepareTemplate($this->template[$templateName][$arguments['locale']]['message'], $variables);
            $subject = $this->prepareTemplate($this->template[$templateName][$arguments['locale']]['subject'], $variables);
        } else {
            $template = $this->prepareTemplate($this->template[$templateName]['en']['message'], $variables);
            $subject = $this->prepareTemplate($this->template[$templateName]['en']['subject'], $variables);
        }

        $mail
            ->setFrom(array($arguments['sender']->getElectronicAddresses()[0]->getIdentifier() => $arguments['sender']->getName()))
            ->setTo(array($arguments['recipient']->getElectronicAddresses()[0]->getIdentifier() => $arguments['recipient']->getName()))
            ->setSubject($subject);


        $mail->setBody($template, 'text/plain');

        $mail->send();

        return $mail->isSent();
    }

    /**
     * @param string $input
     * @param array $variables
     * @return array|string|null
     */
    private function prepareTemplate(string $input, array $variables): array|string|null
    {
        $regex = '/\{([^}]+)\}/';

        return preg_replace_callback($regex, function ($matches) use ($variables) {
            if (empty($matches)) return '';
            $variableName = $matches[1];
            return $variables[$variableName] ?? '';
        }, $input, -1, $count, PREG_UNMATCHED_AS_NULL);
    }
}
