<?php
/*
Plugin Name: FOSSASIA COMMUNITY MAP
Plugin URI: http://www.yourpluginurlhere.com/
Version: 0.1
Author: FOSSASIA
Description: Highly customizable map to show open-source communities in Asia.
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_shortcode( 'fossasia_map' , 'fossasia_map_shortcode_handler' );

function load_fossasia_map_deps($plugin_url) {
	wp_enqueue_style( 'leaflet', $plugin_url . '/external/leaflet/leaflet.css' );
	wp_enqueue_style( 'MarkerCluster', $plugin_url . '/external/leaflet/MarkerCluster.css' );
	wp_enqueue_style( 'MarkerCluster.Default', $plugin_url . '/external/leaflet/MarkerCluster.Default.css' );
	wp_enqueue_style( 'leaflet-button-control', $plugin_url . '/external/leaflet/leaflet-button-control.css' );
	wp_enqueue_style( 'fossasia_community_map', $plugin_url . '/stylesheets/community_map.css' );

	wp_enqueue_script( 'leaflet', $plugin_url . '/external/leaflet/leaflet.js');
	wp_enqueue_script( 'leaflet-button-control', $plugin_url . '/external/leaflet/leaflet-button-control.js');
	wp_enqueue_script( 'leaflet.markercluster', $plugin_url . '/external/leaflet/leaflet.markercluster.js');
	wp_enqueue_script( 'underscore', $plugin_url . '/external/underscore/underscore.min.js');
	wp_enqueue_script( 'fossasia_community_map', $plugin_url . '/javascripts/community_map.js', array('jquery'));
	wp_enqueue_script( 'fossasia_community_map_wordpress', $plugin_url . '/javascripts/community_map_wordpress.js', array('fossasia_community_map'));
}

function fossasia_map_shortcode_handler($options) {
	$MIN_WIDTH = 700;
	$MIN_HEIGHT = 500;
	$defaultsOpts = array(
		'width' => '400',
		'height' => '200',
		'geoJSONUrl' => 'http://api.fossasia.net/map/ffGeoJsonp.php',
		'name' => '',
		'zoom' => 3
	);

	$options = shortcode_atts($defaultsOpts, $options);
	if (intval($options['width']) < $MIN_WIDTH) {
		$options['width'] = $MIN_WIDTH;
	}
	if (intval($options['height']) < $MIN_HEIGHT) {
		$options['height'] = $MIN_HEIGHT;
	}
	extract($options);


	$plugin_url = plugin_dir_url( __FILE__ );
	load_fossasia_map_deps($plugin_url);	
	wp_localize_script( 'fossasia_community_map_wordpress', 'userOptions', array_merge($options, array('currentDir' => $plugin_url)));

	echo "<div id='fossasia-community-map' style='width : {$width}px; height : {$height}px'></div>";
?>
	<style>
		.community-popup ul.contacts li.contact a {
			background: url(<?php echo $plugin_url;?>images/contact_icons.png);
		}
		/*.leaflet-filter-control-toggle {
  			background-image: url(<?php echo $plugin_url;?>images/layers.png);
		}*/
	</style>
	<script type="text/template" class="template" id="community-popup">
	<div class="community-popup" data-id="<%- props.shortname %>" >
	<% if ( props.name ) { %>
	<h2><a href="<%- props.url %>" target="_window"><%- props.name %></a></h2>
	<% } %>

	<% if ( props.logo ) { %>
	<img class="logo" src="<%- props.logo %>" />
	<% } %>

	<% if (props.metacommunity) { %>
	<h3><%- props.metacommunity %></h3>
	<% } %>

	<% if (props.city) { %>
	<div class="city"><%- props.city  %></div>
	<% } %>

	<% if (props.nodes) { %>
	<div class="nodes">
	 Zug&auml;nge: <%- props.nodes  %>
	 <% if (props.state && props.age) { %>
	    <span class="state <%- props.state  %>" title="Die letzte Aktualisierung der Daten war vor <%- props.age  %> Tagen">(<%- props.state  %>)</span>
	 <% } %>
	</div>
	<% } %>

	<% if (props.phone) { %>
	<div class="phone">&#9742; <%- props.phone  %></div>
	<% } %>

	<ul class="contacts" style="height:<%- Math.round(props.contacts.length/6+0.4)*30+10 %>px; width: <%- 6*(30+5)%>px;">
	<% _.each(props.contacts, function(contact, index, list) { %>
	<li class="contact">
	<a href="<%- contact.url %>" class="button <%- contact.type %>" target="_window"></a>
	</li>
	<% }); %>
	</ul>
	<div class="events">
	<label style="display:block">Recent events</label>
	<iframe id="latest-event" class="embed-timeline" src="http://timeline-fossasia-api.herokuapp.com/embed.php?source=hassberge&order=oldest-first&limit=1&title=null&disableScroll=true" scrolling="no"></iframe>

	<iframe id="upcoming-event" class="embed-timeline" src="http://timeline-fossasia-api.herokuapp.com/embed.php?source=hassberge&order=latest-first&limit=1&until=now&title=null&disableScroll=true" scrolling="no"></iframe>
	</div>
	</div>
	</script>
<?php
	return;
}
?>