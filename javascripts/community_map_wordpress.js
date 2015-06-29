/**
 * Bridge script to call community_map.js with user options
 */
var FOSSASIACommunityMapPlugin = function(options) {
	var widget = FFCommunityMapWidget({
		divId : 'fossasia-community-map',
		currentDir : options.currentDir,
        geoJson: options.geoJSONUrl,
        scrollByMousewheel : false,
        hideLocationButton: false,
        hideLayerControl: false,
        hideInfoBox: true 
    });
}(userOptions);