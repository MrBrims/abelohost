import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import { PanelBody, SelectControl, Spinner } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useEffect, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import "./editor.scss";

const OPENWEATHER_API_KEY = window._wpSettings?.weatherApiKey || "";

// Weather retrieval function
async function fetchWeatherData(lat, lon) {
	try {
		const response = await fetch(
			`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${OPENWEATHER_API_KEY}&units=metric&lang=en`,
		);

		if (!response.ok) {
			throw new Error(__("Error loading weather data", "storefront-child"));
		}

		return await response.json();
	} catch (error) {
		console.error("Error:", error);
		throw error;
	}
}

export default function Edit({ attributes, setAttributes }) {
	const { option } = attributes;
	const [weather, setWeather] = useState(null);
	const [weatherLoading, setWeatherLoading] = useState(false);
	const [weatherError, setWeatherError] = useState(null);

	const { cities, isLoading } = useSelect((select) => ({
		cities: select("core").getEntityRecords("postType", "cities"),
		isLoading: select("core").isResolving("getEntityRecords", [
			"postType",
			"cities",
		]),
	}));

	useEffect(() => {
		const fetchWeather = async () => {
			if (!selectedCity) return;

			setWeatherLoading(true);
			try {
				const data = await fetchWeatherData(
					selectedCity.meta["abelohost-latitude"],
					selectedCity.meta["abelohost-longitude"],
				);
				setWeather(data);
				setWeatherError(null);
			} catch (error) {
				setWeatherError(error.message);
			} finally {
				setWeatherLoading(false);
			}
		};

		fetchWeather();
	}, [option, cities]);

	const selectedCity = cities?.find((city) => city.id.toString() === option);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Widget weathers setting", "storefront-child")}>
					{isLoading ? (
						<Spinner />
					) : (
						<SelectControl
							label={__("Select a city", "storefront-child")}
							help={__(
								"Weather information will be displayed for selected city",
								"storefront-child",
							)}
							value={option}
							options={
								cities
									? cities.map((city) => ({
											label: city.title.rendered,
											value: city.id.toString(),
									  }))
									: []
							}
							onChange={(val) => {
								setAttributes({ option: val });
							}}
						/>
					)}
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>
				<h3> {__("City weathers", "storefront-child")}</h3>
				{weatherLoading && <Spinner />}

				{weatherError && (
					<div className="notice notice-error">
						{__("Error loading weather data:", "storefront-child")}{" "}
						{weatherError}
					</div>
				)}

				{weather && (
					<div className="weather-widget">
						<h2>{selectedCity.title.rendered}</h2>
						<div className="weather-info">
							<p>
								{__("Temperature:", "storefront-child")} {weather.main.temp}°C
							</p>
							<p>
								{__("Conditions:", "storefront-child")}{" "}
								{weather.weather[0].description}
							</p>
							<p>
								{__("Humidity:", "storefront-child")} {weather.main.humidity}%
							</p>
							<p>
								{__("Wind Speed:", "storefront-child")} {weather.wind.speed} m/s
							</p>
						</div>
					</div>
				)}
			</div>
		</>
	);
}
