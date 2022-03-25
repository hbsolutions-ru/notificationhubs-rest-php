<?php

namespace Openpp\NotificationHubsRest\Notification;

class GcmNotification extends AbstractNotification
{
    /**
     * @var string[]
     */
    private $supportedOptions = [
        'collapse_key',
        'delay_while_idle',
        'time_to_live',
        'restricted_package_name',
        'dry_run',
    ];

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return 'gcm';
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return 'application/json;charset=utf-8';
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload()
    {
        $customPayloadData = null;

        if (!empty($this->options)) {
            if (!empty($this->options['custom-payload-data']) && is_array($this->options['custom-payload-data'])) {
                $customPayloadData = $this->options['custom-payload-data'];
            }
            $payload = array_intersect_key($this->options, array_fill_keys($this->supportedOptions, 0));
        } else {
            $payload = [];
        }

        if (is_array($this->alert)) {
            $payload += ['data' => $this->alert];
        } elseif (is_scalar($this->alert)) {
            $payload += ['data' => ['message' => $this->alert]];
        } else {
            throw new \RuntimeException('Invalid alert.');
        }

        if (!empty($customPayloadData)) {
            $payload += $customPayloadData;
        }

        return json_encode($payload);
    }
}
