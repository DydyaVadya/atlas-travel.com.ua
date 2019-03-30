<div class="wrap">
	<h2>Каталог - утиліти</h2>
	<form method="post" action="">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<input type="submit" class="button" name="updateImages" value="Обновить изображения отелей" />
				</th>
				<td>
					Заново загрузить urls изображения из otpusk.com
				</td>
			</tr>
            <tr>
                <th scope="row">
                    Виджет описания отеля
                </th>
                <td>
                    <input type="submit" class="button ajaxBtn" name="enableAllOtpuskWidgets" value="Включить во всех отелях" />
                    <input type="submit" class="button ajaxBtn" name="disableAllOtpuskWidgets" value="Отключить во всех отелях" /><br>
                    <div class="spinner"></div>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Виджет отзывов об отеле
                </th>
                <td>
                    <input type="submit" class="button ajaxBtn" name="enableAllTurpravdaWidgets" value="Включить во всех отелях" />
                    <input type="submit" class="button ajaxBtn" name="disableAllTurpravdaWidgets" value="Отключить во всех отелях" /><br>
                    <div class="spinner"></div>
                </td>
            </tr>
		</table>
		<pre>
		<?php
		if(isset($_POST['updateImages'])) {
			$key = ODevCatalogManager::getOtpuskSoapApiKey();
			try {
				$cli = new SoapClient('http://api.otpusk.com?wsdl', array(
					'exceptions'         => true,
					'trace'              => true,
					'connection_timeout' => 9000, // секунд
					'keep_alive'         => 1,
				));
				print_r($cli->__getFunctions());
				$login = $cli->getAuthorization($key);
				global $wpdb;
				if ($login)
				{
					//*
					$countries = $cli->getCountryList($key);
					foreach ($countries->countries AS $country) {
						print_r($country);

						// получение списка курортов
						$cities = $cli->getCityList(
							$key,
							array('countryId' => array('id' => $country->countryId->id))
						);
						foreach ($cities->cities AS $city) {
							print_r($city);

							// получение списка отелей
							$hotels = $cli->getHotelList(
								$key,
								array(
									'countryId' => array('id' => $city->countryId->id),
									'cityId' => array('id' => $city->cityId->id)
								)
							);
							foreach ($hotels->hotels AS $hotel) {
								$image = $hotel->images;
								if(!empty($image[0]->href)) {
									$wpdb_return = $wpdb->update('odev_catalog_hotel',
										array('otpusk_image_url' => $image[0]->href),
										array('otpusk_id' => $hotel->hotelId->id)
									);
								}
							}
						} // end cities
					} // end countrie
					//*/
				}
			}
			catch (Exception $e) {
				echo '---------------' . PHP_EOL;
				echo 'ERROR: ' . $e->getMessage();
				echo '---------------' . PHP_EOL;
				echo 'REQUEST HEADERS:' . PHP_EOL . print_r($cli->__getLastRequestHeaders(), 1) . PHP_EOL;
				echo 'REQUEST:' . PHP_EOL . PHP_EOL . print_r($cli->__getLastRequest(), 1) . PHP_EOL;
				echo '---------------' . PHP_EOL;
				echo 'RESPONSE HEADERS:' . PHP_EOL . print_r($cli->__getLastResponseHeaders(), 1) . PHP_EOL;
				echo 'Response:' . PHP_EOL . PHP_EOL . print_r($cli->__getLastResponse(), 1) . PHP_EOL;
				echo '---------------' . PHP_EOL;
			}
		}
		?>
		</pre>
	</form>
</div>

<style>
    .spinner {
        float: none !important;
    }
</style>