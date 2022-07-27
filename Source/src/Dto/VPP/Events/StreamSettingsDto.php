<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class StreamSettingsDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Streaming providerr",
     *              "example"="Zoom",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public ?string $streamingHost = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Stream access link",
     *              "example"="https://zoom/url/zum/kurs",
     *              "maxLength"=190,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="190")
     * @Assert\Type("string")
     */
    public ?string $streamLink = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Password",
     *              "example"="abc123",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public ?string $streamPassword = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Meeting-ID",
     *              "example"="123456",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $meetingId = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Additional informations",
     *              "example"="Heute abweichende Kursleitung!",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $additionalInformation = null;

}
