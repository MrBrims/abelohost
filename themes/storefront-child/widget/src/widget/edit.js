import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import { PanelBody, SelectControl, Spinner } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import "./editor.scss";

export default function Edit({ attributes, setAttributes }) {
	const { option } = attributes;

	const { cities, isLoading } = useSelect((select) => {
		return {
			cities: select("core").getEntityRecords("postType", "cities"),
			isLoading: select("core").isResolving("getEntityRecords", [
				"postType",
				"cities",
			]),
		};
	});
	console.log(cities);

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
				<p>
					{__(
						"City is selected in the widget settings (in the sitebar on the right)!",
						"storefront-child",
					)}
				</p>
				{isLoading ? (
					<Spinner />
				) : selectedCity ? (
					<div className="weather-widget">
						<h2>{selectedCity.title.rendered}</h2>
						<div className="coordinates">
							<p>
								{__("Latitude:", "storefront-child")}{" "}
								{selectedCity.meta["abelohost-latitude"]}
							</p>
							<p>
								{__("Longitude:", "storefront-child")}{" "}
								{selectedCity.meta["abelohost-longitude"]}
							</p>
						</div>
					</div>
				) : (
					<p>{__("Select a city in settings", "storefront-child")}</p>
				)}
			</div>
		</>
	);
}
