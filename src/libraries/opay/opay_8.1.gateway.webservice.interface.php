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

interface OpayGatewayWebServiceException
{
    const COMMUNICATION_WITH_SERVER_ERROR = '21101';
    const JSON_DECODING_ERROR = '21102';
    const WRONG_JSON_FORMAT = '21103';

}

interface OpayGatewayWebServiceInterface
{

/////
//  Funkcijos, naudojamos gauti iš OPAY galimas naudoti reikšmes
// 

    /**
     * Funkcija kreipiasi į $url nurodytą web servisą ir grąžina rezultatus,
     *
     * @param string $url - Pilnas HTTPS adresas kartu su nurodytu protokolu. Pvz.: https://gateway.opay.lt/pay/listchannels/
     * @param array $parametersArray - Masyvas parametrų, kurie bus siunčiami. Masyvo asociatyvus indeksas atspindi parametro pavadinimą, o reikšmė - parametro reikšmę.
     * @param boolean $sendEncoded - Jei nurodyta reikšmė TRUE, tada visi parametrai bus suspausti ir siunčiami kaip vienas parametras pavadinimu "encoded".
     *
     * @return array                   - Metodas grąžina masyvą. Kurio struktūra tokia:
     *
     *                                 array(
     *                                       'response' => array(
     *                                                       'language' => <kalbos kodas, kokia grąžinamas atsakykmas. pvz "LTL">
     *                                                       'result'   => <rezultatas priklauso nuo to, į kokį web servisą kreipiamasi>
     *                                                       'errors'   => <tuščias masyvas jei klaidų neįvyko> ARBA array(
     *                                                                                                                       '0' => array(
     *                                                                                                                                 'code'      => <klaidos kodas>,
     *                                                                                                                                 'message'   => <klaidos tekstas>,
     *                                                                                                                                 'solutions' => <tuscias masyvas> ARBA masyvas tekstų
     *                                                                                                                                   )
     *                                                                                                                    )
     *                                       )
     *                                 )
     *
     * Grąžinamo rezultato pavyzdys:
     *
     *  Array
     *(
     *    [response] => Array
     *    (
     *        [language] => LIT
     *        [result] => Array
     *        (
     *            [banklink] => Array
     *            (
     *                [group_title] => Mokėjimas per internetinę bankininkystę
     *                [channels] => Array
     *                (
     *                    [banklink_swedbank] => Array
     *                    (
     *                        [channel_name] => banklink_swedbank
     *                        [title] => Swedbank bankas
     *                        [logo_urls] => Array
     *                            (
     *                                [color_33px] => https://widgets.opay.lt/img/banklink_swedbank_color_0x33.png
     *                                [color_49px] => https://widgets.opay.lt/img/banklink_swedbank_color_0x49.png
     *                            )
     *                    )
     *                )
     *            )
     *        )
     *        [errors] => Array
     *        (
     *            [0] => Array
     *            (
     *                [code] => UNKNOWN_SHOW_CHANNEL_NAMES
     *                [message] => Nežinoma (-os) reikšmė(-ės) dfdgfsg pateikta(-os) parametre [ show_channels ].
     *                [solutions] => Array
     *                (
     *                    [0] => Detalesnę informaciją apie [ show_channels ] ir [ hide_channels ] parametrus galite rasti OPAY mokėjimų sistemos specifikacijoje
     *                )
     *            )
     *        )
     *    )
     *)
     *
     */

    public function webServiceRequest($url, $parametersArray, $sendEncoded = true);

}


?>
