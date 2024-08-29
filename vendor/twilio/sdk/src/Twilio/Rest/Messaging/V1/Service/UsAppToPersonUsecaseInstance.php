<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Messaging
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Messaging\V1\Service;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;


/**
 * @property array[]|null $usAppToPersonUsecases
 */
class UsAppToPersonUsecaseInstance extends InstanceResource
{
    /**
     * Initialize the UsAppToPersonUsecaseInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $messagingServiceSid The SID of the [Messaging Service](https://www.twilio.com/docs/messaging/api/service-resource) to fetch the resource from.
     */
    public function __construct(Version $version, array $payload, string $messagingServiceSid)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'usAppToPersonUsecases' => Values::array_get($payload, 'us_app_to_person_usecases'),
        ];

        $this->solution = ['messagingServiceSid' => $messagingServiceSid, ];
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        return '[Twilio.Messaging.V1.UsAppToPersonUsecaseInstance]';
    }
}

