import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
	Notice,
	PanelBody,
	SelectControl,
	Spinner,
} from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useEffect, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import "./editor.scss";

// API key for OpenWeatherMap, obtained from the theme's global settings
const OPENWEATHER_API_KEY = storefrontChildWeatherBlock.apiKey;

/**
 * Function for retrieving weather data from OpenWeather API
 * @param {number} lat - Location latitude
 * @param {number} lon - Longitude
 * @returns {Promise<object>} Weather data object
 * @throws {Error} In case of API request error
 */
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

/**
 * Main component of weather block editing
 * @param {object} props - Component properties
 * @param {object} props.attributes - Block attributes
 * @param {function} props.setAttributes - Attribute update function
 */
export default function Edit({ attributes, setAttributes }) {
	const { option } = attributes;
	const [weather, setWeather] = useState(null);
	const [weatherLoading, setWeatherLoading] = useState(false);
	const [weatherError, setWeatherError] = useState(null);

	// Getting a list of cities from WordPress storage
	const { cities, isLoading } = useSelect((select) => ({
		cities: select("core").getEntityRecords("postType", "cities"),
		isLoading: select("core").isResolving("getEntityRecords", [
			"postType",
			"cities",
		]),
	}));

	// Effect for loading weather data when the selected city changes
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

	// Search for the selected city in the list
	const selectedCity = cities?.find((city) => city.id.toString() === option);

	return (
		<>
			{/* Control panel in the editor sidebar */}
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

			{/* The main content of the block */}
			<div {...useBlockProps()}>
				<h3> {__("City weathers", "storefront-child")}</h3>
				{!OPENWEATHER_API_KEY && (
					<Notice status="error" isDismissible={false}>
						{__(
							"API key is not configured. Please set it in Weather Settings.",
							"storefront-child",
						)}
					</Notice>
				)}

				{weatherLoading && <Spinner />}

				{weatherError && (
					<div className="notice notice-error">
						{__("Error loading weather data:", "storefront-child")}{" "}
						{weatherError}
					</div>
				)}

				{/* Weather data box */}
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
