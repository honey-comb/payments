<?php
/**
 * @copyright 2018 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */
declare(strict_types = 1);

namespace HoneyComb\Payments\DTO;

use HoneyComb\Starter\DTO\HCBaseDTO;

/**
 * Class HCPaymentUserDTO
 * @package HoneyComb\Payments\DTO
 */
class HCPaymentUserDTO extends HCBaseDTO
{
    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $firstName;

    /**
     * @var string|null
     */
    private $lastName;

    /**
     * @var string|null
     */
    private $street;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $state;

    /**
     * @var string|null
     */
    private $zip;

    /**
     * @var string|null
     */
    private $countryCode;

    /**
     * @var string|null
     */
    private $phone;

    /**
     * @var string|null
     */
    private $paytext;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return HCPaymentUserDTO
     */
    public function setEmail(string $email): HCPaymentUserDTO
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return HCPaymentUserDTO
     */
    public function setFirstName(string $firstName): HCPaymentUserDTO
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return HCPaymentUserDTO
     */
    public function setLastName(string $lastName): HCPaymentUserDTO
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return HCPaymentUserDTO
     */
    public function setStreet(string $street): HCPaymentUserDTO
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return HCPaymentUserDTO
     */
    public function setCity(string $city): HCPaymentUserDTO
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return HCPaymentUserDTO
     */
    public function setState(string $state): HCPaymentUserDTO
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     * @return HCPaymentUserDTO
     */
    public function setZip(string $zip): HCPaymentUserDTO
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return HCPaymentUserDTO
     */
    public function setCountryCode(string $countryCode): HCPaymentUserDTO
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return HCPaymentUserDTO
     */
    public function setPhone(?string $phone): HCPaymentUserDTO
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaytext(): ?string
    {
        return $this->paytext;
    }

    /**
     * @param string $paytext
     * @return HCPaymentUserDTO
     */
    public function setPaytext(string $paytext): HCPaymentUserDTO
    {
        $this->paytext = $paytext;

        return $this;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'email' => $this->getEmail(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'street' => $this->getStreet(),
            'city' => $this->getCity(),
            'state' => $this->getState(),
            'zip' => $this->getZip(),
            'countryCode' => $this->getCountryCode(),
            'phone' => $this->getPhone(),
            'paytext' => $this->getPaytext(),
        ];
    }
}
