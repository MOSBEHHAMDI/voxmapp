<template>
  <div>
    <div class="extra_actions">
      <div class="vertical-buttons">
        <v-btn v-if="options?.style" icon="mdi-map-outline" size="large" @click="openMapStyles=true"
        />
        <v-btn v-if="options?.currentLocation" ref="locateBtn" class="mt-1" icon="mdi-crosshairs-gps" size="large"
               @click="showCurrentLocation"
        />
        <v-btn v-if="options?.dataPointsPage" class="mt-1" icon="mdi-map-marker-multiple" size="large"
               @click="$router.push('/dataPoints')"
        />
      </div>
      <div v-if="options?.filter" class="filter-btn">
        <v-btn icon="mdi-filter-outline main" size="large" @click="openMapFilters=true"
        />
      </div>
    </div>
    <div :id="id" :style="mapStyle" class="map">
      <div v-if="options?.tracking" class="tracking_container">
        <div class="text-center pa-4">
          <v-dialog
              v-model="trackingPopup"
              max-width="400"
              persistent
          >
            <template v-slot:activator="{ props: activatorProps }">
              <v-btn v-if="trackingOn" id="tracking-title" class="tracking-title" v-bind="activatorProps"
                     @click="trackingPopup = true">{{ tr('Live tracking is ON') }}
              </v-btn>
            </template>
            <v-card v-if="trackingPopup" :text="tr('clicking on stop the live tracking function will be stopped.')"
                    :title="tr('Live tracking')"
                    class="text-center rounded-xl">
              <v-divider></v-divider>
              <template v-slot:actions>
                <v-row class="text-blue">
                  <v-col class="border-8">
                    <v-btn @click="cancelTracking">
                      {{ tr('Cancel') }}
                    </v-btn>
                  </v-col>
                  <v-col class="border-8">
                    <v-btn @click="saveTracking">
                      {{ tr('Stop') }}
                    </v-btn>
                  </v-col>
                </v-row>
              </template>
            </v-card>
          </v-dialog>
        </div>
      </div>
    </div>
    <v-bottom-sheet v-model="openMapStyles">
      <v-card class="white-bg">
        <v-card-title>
          <p class="main ">{{ tr('Choose map style') }}</p>
        </v-card-title>
        <v-card-text class="card-text">
          <v-row>
            <v-col>
              <v-card elevation="0" max-height="60%">
                <v-img class="rounded" src="../../assets/defaultMap.png"
                       @click="updateMap('default')"></v-img>
              </v-card>
              <div class="text-center mt-1">
                {{ tr('Default') }}
              </div>
            </v-col>
            <v-col>
              <v-card elevation="0" max-height="60%">
                <v-img class="rounded" src="../../assets/satelliteMap.png"
                       @click="updateMap('satellite')"></v-img>
              </v-card>
              <div class="text-center mt-1">
                {{ tr('Satellite') }}
              </div>
            </v-col>

          </v-row>
          <v-row>
            <v-col>
              <v-btn block color="warning" variant="outlined" @click="openMapStyles = false">{{ tr('Close') }}</v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <v-bottom-sheet v-model="openMapFilters" max-height="50%">
      <v-card class="pale-bg">
        <v-card-title class="custom-bg">
          <p class="main ">{{ tr('Filters on map') }}</p>
        </v-card-title>
        <v-card-text class="card-text mb-6">
          <p class="main">{{ tr('Filter By Status') }}</p>
          <v-row v-for="status in $dataPoint_store.dataPoint_page_info.statuses" style="max-height:40px">
            <v-col class="mt-4">
              <v-icon color="green">mdi-map-marker</v-icon>
              {{ status.name + ' ' }} Data point
            </v-col>
            <v-col c cols="2">
              <v-switch v-model="statusesFilter" :value="status" color="warning"></v-switch>
            </v-col>
          </v-row>
          <p class="main mt-10">{{ tr('Filter By Type') }}</p>
          <v-row v-for="type in $dataPoint_store.dataPoint_page_info.dataPointTypes" style="max-height:40px">
            <v-col class="mt-4">
              <v-icon color="green">mdi-map-marker</v-icon>
              {{ type.name + ' Data Point' }}
            </v-col>
            <v-col cols="2">
              <v-switch v-model="typesFilter" :value="type" color="warning"></v-switch>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions class="card-actions custom-bg">
          <v-row>
            <v-col>
              <v-btn block color="warning" variant="outlined" @click="openMapFilters = false">{{ tr('Close') }}</v-btn>
            </v-col>
            <v-col>
              <v-btn block color="warning" variant="flat" @click="applyFilters()">{{ tr('Apply') }}</v-btn>
            </v-col>
            <v-col>
              <v-btn block color="warning" variant="outlined" @click="clearFilters()">{{
                  tr('Clear filters')
                }}
              </v-btn>
            </v-col>
          </v-row>
        </v-card-actions>
      </v-card>
    </v-bottom-sheet>
    <v-dialog v-model="detailDialog" style="margin-bottom: -45vh">
      <v-card style="border-radius: 17px;">
        <Slider></Slider>
        <v-card-title class="main pb-0">
          <h5>{{ $dataPoint_store.currentDataPoint.name }}</h5>
        </v-card-title>
        <v-card-subtitle class="pt-0">{{ $dataPoint_store.currentDataPoint.address }}</v-card-subtitle>
        <v-card-text class="pt-1 pl-4">
          <p class="pa-0">Type: <span class=" text-green">{{
              $dataPoint_store.currentDataPoint.dataPointType.name
            }}</span></p>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="pt-0 pb-0">
          <v-btn color="danger" density="comfortable" variant="text" @click="detailDialog=false">close</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="success" density="comfortable" variant="text" @click="viewDataPoint()">View
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import Feature from 'ol/Feature';
import Point from 'ol/geom/Point';
import {Fill, Icon, Style, Text} from 'ol/style';
import {Cluster, Vector as VectorSource} from 'ol/source';
import "ol/ol.css";
import {fromLonLat} from 'ol/proj';
import XYZ from 'ol/source/XYZ';
import {Group} from "ol/layer";
import Slider from "@/mobile/components/Slider.vue";
import {click} from 'ol/events/condition';
import LineString from 'ol/geom/LineString';
import Stroke from 'ol/style/Stroke';
import Map from 'ol/Map';
import View from 'ol/View';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';
import {Select} from 'ol/interaction';
import Draw from 'ol/interaction/Draw';
import GeoJSON from 'ol/format/GeoJSON';
import Snap from 'ol/interaction/Snap';
import Polygon from 'ol/geom/Polygon';
import CircleStyle from "ol/style/Circle";
import {createEmpty, extend, getHeight, getWidth} from "ol/extent";
import monotoneChainConvexHull from "monotone-chain-convex-hull";

const convexHullFill = new Fill({
  color: 'rgba(255, 153, 0, 0.4)',
});
const convexHullStroke = new Stroke({
  color: 'rgba(204, 85, 0, 1)',
  width: 1.5,
});
const outerCircleFill = new Fill({
  color: 'rgba(255, 153, 102, 0.3)',
});
const innerCircleFill = new Fill({
  color: 'rgba(255, 165, 0, 0.7)',
});
const textFill = new Fill({
  color: '#fff',
});
const textStroke = new Stroke({
  color: 'rgba(0, 0, 0, 0.6)',
  width: 3,
});
const innerCircle = new CircleStyle({
  radius: 14,
  fill: innerCircleFill,
});
const outerCircle = new CircleStyle({
  radius: 20,
  fill: outerCircleFill,
});

const markerColors = {'Health Facility': '#301fc8', 'Monument': '#bf1283', 'School': '#ff7f00', 'Warehouse': '#ffbc11', 'Judiciary': '#000080'};

const markerStyleCache =  []

/**
 * Single feature style, users for clusters with 1 feature and cluster circles.
 * @param {Feature} clusterMember A feature from a cluster.
 * @return {Style} An icon style for the cluster member's location.
 */
function clusterMemberStyle(clusterMember) {
  let datapointType = clusterMember.get('dataPoint')?.dataPointType
  let datapointTypeName = datapointType ? datapointType.name : 'Health Facility'
  if (typeof markerStyleCache[datapointTypeName] === "undefined") {
    let color = markerColors[datapointTypeName].replace('#', '%23')
    let typeColor = color ?? 'red';
    markerStyleCache[datapointTypeName] =
        new Style({
          image: new Icon({
            src: 'data:image/svg+xml;utf8, ' + `<svg fill="${typeColor}" xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="-5.0 -10.0 110.0 135.0"> <path d="m55.41 92.141c9.3594-13.184 27.59-40.594 27.59-54.141 0-18.227-14.773-33-33-33s-33 14.773-33 33c0 13.547 18.23 40.957 27.59 54.141 2.6758 3.7695 8.1445 3.7695 10.82 0zm-5.4102-37.141c9.3906 0 17-7.6094 17-17s-7.6094-17-17-17-17 7.6094-17 17 7.6094 17 17 17z" fill-rule="evenodd"/></svg>`
          })
        });
  }
  return markerStyleCache[datapointTypeName]
}

let clickFeature, clickResolution;
/**
 * Style for clusters with features that are too close to each other, activated on click.
 * @param {Feature} cluster A cluster with overlapping members.
 * @param {number} resolution The current view resolution.
 * @return {Style|null} A style to render an expanded view of the cluster members.
 */
function clusterCircleStyle(cluster, resolution) {
  if (cluster !== clickFeature || resolution !== clickResolution) {
    return null;
  }
  const clusterMembers = cluster.get('features');
  const centerCoordinates = cluster.getGeometry().getCoordinates();
  return generatePointsCircle(
    clusterMembers.length,
    cluster.getGeometry().getCoordinates(),
    resolution,
  ).reduce((styles, coordinates, i) => {
    //const point = new Point(coordinates);
    const line = new LineString([centerCoordinates, coordinates]);
    styles.unshift(
      new Style({
        geometry: line,
        stroke: convexHullStroke,
      }),
    );
    styles.push(
      clusterMemberStyle(
        new Feature({...clusterMembers[i].getProperties()})
      ),
    );
    return styles;
  }, []);
}

/**
 * From
 * https://github.com/Leaflet/Leaflet.markercluster/blob/31360f2/src/MarkerCluster.Spiderfier.js#L55-L72
 * Arranges points in a circle around the cluster center, with a line pointing from the center to
 * each point.
 * @param {number} count Number of cluster members.
 * @param {Array<number>} clusterCenter Center coordinate of the cluster.
 * @param {number} resolution Current view resolution.
 * @return {Array<Array<number>>} An array of coordinates representing the cluster members.
 */
function generatePointsCircle(count, clusterCenter, resolution) {
  const circleDistanceMultiplier = 1;
  const circleFootSeparation = 28;
  const circleStartAngle = Math.PI / 2;
  const circumference =
    circleDistanceMultiplier * circleFootSeparation * (2 + count);
  let legLength = circumference / (Math.PI * 2); //radius from circumference
  const angleStep = (Math.PI * 2) / count;
  const res = [];
  let angle;

  legLength = Math.max(legLength, 35) * resolution; // Minimum distance to get outside the cluster icon.

  for (let i = 0; i < count; ++i) {
    // Clockwise, like spiral.
    angle = circleStartAngle + i * angleStep;
    res.push([
      clusterCenter[0] + legLength * Math.cos(angle),
      clusterCenter[1] + legLength * Math.sin(angle),
    ]);
  }

  return res;
}

let hoverFeature;
/**
 * Style for convex hulls of clusters, activated on hover.
 * @param {Feature} cluster The cluster feature.
 * @return {Style|null} Polygon style for the convex hull of the cluster.
 */
function clusterHullStyle(cluster) {
  if (!hoverFeature) {
    return null;
  }
  const originalFeatures = cluster.get('features');
  const hoverFeatures = hoverFeature.get('features');
  if (originalFeatures.reduce((a, f) => f.ol_uid + "-" + (a.ol_uid || a)) !== hoverFeatures.reduce((a, f) => f.ol_uid + "-" + (a.ol_uid || a))) {
    return null;
  }
  const points = originalFeatures.map((feature) =>
    feature.getGeometry().getCoordinates(),
  );
  return new Style({
    geometry: new Polygon([monotoneChainConvexHull(points)]),
    fill: convexHullFill,
    stroke: convexHullStroke,
  });
}

function clusterStyle(feature) {
  const size = feature.get('features').length;
  if (size > 1) {
    return [
      new Style({
        image: outerCircle,
      }),
      new Style({
        image: innerCircle,
        text: new Text({
          text: size.toString(),
          fill: textFill,
          stroke: textStroke,
        }),
      }),
    ];
  }
  const originalFeature = feature.get('features')[0];
  return clusterMemberStyle(originalFeature);
}

export default {
  props: ['latLong', 'zoomControl', 'mapStyle', 'id', 'clustered', 'dataPoints', 'mapFeatureObject', 'selectEvent', 'options'],
  components: {Slider},
  watch: {
    markers: {
      handler(newValue) {
        this.markers = newValue;
        this.updateMarkers();
      },
    }
  },
  data() {
    return {
      trackingPopup: false,
      trackingOn: false,
      map: null,
      key: 'AIzaSyAoK7Q1H7l2bsli_TOMEbJz5zZ0NIWeXnU',
      layerGroup: {},
      geoLocationData: {},
      statusesFilter: [],
      typesFilter: [],
      openMapFilters: false,
      openMapStyles: false,
      detailDialog: false,
      filteredDataPoints: [],
      trackingLayer: null,
      layers: {}
    };
  },
  computed: {
    /*markerColor() {
      return {Active: 'green-bg', Inactive: 'bg-black', Pending: 'bg-blue'}
    },*/
    markers() {
      return this.filteredDataPoints.map(dataPoint => {
        return {markerLatLong: this.getLongLat(dataPoint.latLong), dataPoint}
      })
    },
  },
  mounted() {
    this.$store.mapFeature.data = {}
    const roadLayer = this.getTileLayer('h');
    this.layerGroup['default'] = new Group({
      layers: [
        this.getTileLayer('m'), roadLayer
      ]
    });
    this.layerGroup['satellite'] = new Group({
      layers: [
        this.getTileLayer('s'), roadLayer
      ]
    });
    this.initMap();
  },
  methods: {
    getLayer(name, features = []) {
      if (!this.layers[name]) {
        const source = new VectorSource();
        if (features) {
          source.addFeatures(features);
        }
        const layer = new VectorLayer({
          source
        });
        this.map.addLayer(layer);
        this.layers[name] = layer
      }
      return this.layers[name];
    },
    getTileLayer(c) {
      return new TileLayer({
        source: new XYZ({
          url: `https://mt1.google.com/vt/lyrs=${c}&x={x}&y={y}&z={z}&key=${this.key}`,
          tileSize: 512,
          maxZoom: 22,
        })
      });
    },
    handDrawing() {
      // to simplify our code and avoid duplication , it's better to use (this.vectorSource ;this.vectorLayer) instead of defining and adding new map layers every time
      const vectorSource = new VectorSource();
      const vectorLayer = new VectorLayer({
        source: vectorSource,
      });
      this.map.addLayer(vectorLayer);
      let lastDrawnPoint = null;
      const draw = new Draw({
        source: vectorSource,
        type: 'Point'
      });
      this.map.addInteraction(draw);
      draw.on('drawend', (event) => {
        const feature = event.feature;
        console.log('Drawn Feature Geometry:', feature.getGeometry());
        if (lastDrawnPoint) {
          const line = new LineString([
            lastDrawnPoint.getGeometry().getCoordinates(),
            feature.getGeometry().getCoordinates()
          ]);
          const lineFeature = new Feature({
            geometry: line
          });
          vectorSource.addFeature(lineFeature);
          // Get all features in the vector source
          const features = vectorSource.getFeatures();
          // Get coordinates of all drawn linestrings
          const coordinates = features.map(feat => feat.getGeometry().getCoordinates());
          // Close the polygon by adding the first point to the end
          coordinates.push(coordinates[0]);
          // Create a polygon geometry
          const polygonGeometry = new Polygon([coordinates]);
          // Create a feature for the polygon
          const polygonFeature = new Feature({
            geometry: polygonGeometry
          });
          // Add the polygon feature to the vector source
          vectorSource.addFeature(polygonFeature);
        }

        lastDrawnPoint = feature;
      });
    },
    walkToDraw() {
      navigator.geolocation.watchPosition((pos) => {
        this.$store.mapFeature.data[pos.timestamp] = [pos.coords.longitude, pos.coords.latitude]; //1 set those tracking coords as the polygon boudaries
        //2  call the utils method addFeature if we nedd to save the drawen polygon in history
      })
      // drawing polygon
      const baseVector = new VectorLayer({
        source: new VectorSource({
          format: new GeoJSON(),
          url: 'data/geojson/fire.json',
        }),
        style: {
          'fill-color': 'rgba(255, 0, 0, 0.3)',
          'stroke-color': 'rgba(255, 0, 0, 0.9)',
          'stroke-width': 0.5,
        },
      });
      const drawVector = new VectorLayer({
        source: new VectorSource(),
        style: {
          'stroke-color': 'rgba(100, 255, 0, 1)',
          'stroke-width': 2,
          'fill-color': 'rgba(100, 255, 0, 0.3)',
        },
      });
      const snapInteraction = new Snap({
        source: baseVector.getSource(),
      });
      const drawInteraction = new Draw({
        type: "Polygon",
        source: drawVector.getSource(),
        trace: true,
        traceSource: baseVector.getSource(),
        style: {
          'stroke-color': 'rgba(255, 255, 100, 0.5)',
          'stroke-width': 1.5,
          'fill-color': 'rgba(255, 255, 100, 0.25)',
          'circle-radius': 6,
          'circle-fill-color': 'rgba(255, 255, 100, 0.5)',
        },
      });
      this.map.addInteraction(drawInteraction);
      this.map.addInteraction(snapInteraction);
    },
    pinOnMap() {
      this.map.on('pointerdrag', (event) => {
        const dragCoords = event.coordinate;
        console.log('drag coords', dragCoords);    // update the concerned marker coordinates
      });
    },
    saveTracking() {
      this.trackingPopup = false
      this.trackingOn = false
      this.$utils.addMapFeature('tracking');
    },
    cancelTracking() {
      this.$store.mapFeature.data = {}
      this.map.removeLayer(this.trackingLayer);
      this.trackingLayer = null;
      this.trackingOn = false
      this.trackingPopup = false
    },
    startLiveTracking() {
      this.trackingOn = true
      navigator.geolocation.watchPosition(
          (pos) => {
            this.$store.mapFeature.data[pos.timestamp] = [pos.coords.longitude, pos.coords.latitude];
            this.drawTracking(this.$store.mapFeature.data);
          },
      );
    },
    drawTracking(trackingData) {
      const coordinates = Object.values(trackingData).map(coord => fromLonLat(coord));
      const lineString = new LineString(coordinates);
      const feature = new Feature({
        geometry: lineString,
        name: 'Tracking Path',
      });
      const style = new Style({
        stroke: new Stroke({
          color: '#ffcc33',
          width: 5,
        }),
      });
      feature.setStyle(style);
      this.trackingLayer = this.getLayer('tracking');
      this.trackingLayer.getSource().addFeature(feature);
    },
    updateMap(mapType) {
      this.map.setLayerGroup(this.layerGroup[mapType]);
      this.filteredDataPoints = this.dataPoints || []
      //this.updateMarkers();
      this.openMapStyles = false;
    },
    initMap() {
      const view = new View({
        center: [47.313220, -1.319482],
        zoom: 2,
      });
      this.map = new Map({
        target: this.id,
        view: view
      });
      this.updateMap('default');
    },

    updateMarkers() {
      const features = [];
      //  when we need to draw one single marker
      if (this.mapFeatureObject?.type === 'newPlace') {
        const feature = new Feature(new Point(fromLonLat(this.mapFeatureObject?.data.latLong)));
        features.push(feature);
      }
      //  when we need to add markers depending on dataPoints coords : used for baseline map
      if (this.dataPoints) {
        for (let i = 0; i < this.markers.length; ++i) {
          const coordinates = this.markers[i];
          const feature = new Feature(new Point(fromLonLat(coordinates.markerLatLong)));
          feature.setProperties({
            dataPoint: coordinates.dataPoint
          });
          if (!this.clustered) {
            feature.setStyle(clusterMemberStyle(feature));
          }
          features.push(feature);
        }
      }
      if (this.mapFeatureObject?.type === 'tracking') {
        this.drawTracking(this.mapFeatureObject.data);
      }
      if (features) {
        this.clearMarkers();
        const vectorSource = new VectorSource({
          features
        });
        if (this.selectEvent) {
          const selectInteraction = new Select({
            condition: click,
            style: null,
          });

          selectInteraction.on('select', (e) => {
            const selectedFeature = e.selected[0];
            if (selectedFeature) {
              let featureData = selectedFeature;
              const features = selectedFeature.values_.features;
              if (features) {
                if (features.length > 1) {
                  //Cluster
                  return;
                }
                featureData = features[0];
              }
              const dataPoint = featureData.values_.dataPoint ?? {}
              if (dataPoint && dataPoint.name) { //this condition just to ensure that the returned value is a dataPoint object , if not we dont need to keep the next process
                const extent = selectedFeature.getGeometry().getExtent();
                this.map.getView().fit(extent, {duration: 1000, maxZoom: 18});
                this.$dataPoint_store.currentDataPoint = dataPoint;
                this.detailDialog = true
              }
            }
          });
          //we need to add if (condition) to add interaction only on datapoints markers
          this.map.addInteraction(selectInteraction);
        }
        if (this.clustered) {
          this.applyCluster(vectorSource);
        } else {
          const vectorLayer = new VectorLayer({
            source: vectorSource
          });
          this.map.addLayer(vectorLayer);
        }
      }
    },
    applyCluster(vectorSource) {
      const clusterSource = new Cluster({
        distance: 35,
        source: vectorSource
      });
      const clusters = new VectorLayer({
        source: clusterSource,
        style: clusterStyle
      });
      this.map.addLayer(clusters);

      // Layer displaying the convex hull of the hovered cluster.
      const clusterHulls = new VectorLayer({
        source: clusterSource,
        style: clusterHullStyle,
      });
      this.map.addLayer(clusterHulls);

      // Layer displaying the expanded view of overlapping cluster members.
      const clusterCircles = new VectorLayer({
        source: clusterSource,
        style: clusterCircleStyle,
      });
      this.map.addLayer(clusterCircles);

      //cluster behaviour
      /*this.map.on('pointermove', (event) => {
        clusters.getFeatures(event.pixel).then((features) => {
          if (features[0] !== hoverFeature) {
            // Display the convex hull on hover.
            hoverFeature = features[0];
            clusterHulls.setStyle(clusterHullStyle);
            // Change the cursor style to indicate that the cluster is clickable.
            this.map.getTargetElement().style.cursor =
                hoverFeature && hoverFeature.get('features').length > 1
                    ? 'pointer'
                    : '';
          }
        });
      });*/

      this.map.on('click', (event) => {
        clusters.getFeatures(event.pixel).then((features) => {
          if (features.length > 0) {
            // Display the convex hull on hover.
            hoverFeature = features[0];
            clusterHulls.setStyle(clusterHullStyle);
            // Change the cursor style to indicate that the cluster is clickable.
            this.map.getTargetElement().style.cursor =
                hoverFeature && hoverFeature.get('features').length > 1
                    ? 'pointer'
                    : '';
            //
            const clusterMembers = features[0].get('features');
            if (clusterMembers.length > 1) {
              // Calculate the extent of the cluster members.
              const extent = createEmpty();
              clusterMembers.forEach((feature) =>
                  extend(extent, feature.getGeometry().getExtent()),
              );
              const view = this.map.getView();
              // Zoom to the extent of the cluster members.
              view.fit(extent, {duration: 500, padding: [50, 50, 50, 50]});
            }
          }
        });
      });
    },
    showCurrentLocation() {
      const iconStyle = new Style({
        image: new Icon({
          src: '../../src/assets/icons/map_direction.png',
          scale: 0.3,
        })
      });

      navigator.geolocation.getCurrentPosition((pos) => {
            const source = this.getLayer('current_location').getSource();
            const coords = fromLonLat([pos.coords.longitude, pos.coords.latitude]);
            const feature = new Feature({
              geometry: new Point(coords)
            });
            feature.setStyle(iconStyle);
            source.clear();
            source.addFeature(feature);
            this.map.getView().animate({
              center: coords,
              duration: 100,
              zoom: 3
            });
          },
          function (error) {
            //alert(`ERROR: ${error.message}`);
          },
          {
            enableHighAccuracy: true
          }
      );
      //this.geoLocate()
    },
    /*geoLocate() {
      const view = this.map.getView();

      const geolocation = new Geolocation({
        trackingOptions: {
          enableHighAccuracy: true
        },
        projection: view.getProjection()
      });
      geolocation.setTracking(true);

      let geoLocationData = {
        accuracy: '',
        altitude: '',
        altitudeAccuracy: '',
        heading: '',
        speed: ''
      };

      geolocation.on('change', function () {
        geoLocationData.accuracy = geolocation.getAccuracy();
        geoLocationData.altitude = geolocation.getAltitude();
        geoLocationData.altitudeAccuracy = geolocation.getAltitudeAccuracy();
        geoLocationData.heading = geolocation.getHeading();
        geoLocationData.speed = geolocation.getSpeed();
      });
      this.geoLocationData = geoLocationData;
    },*/
    clearMarkers() {
      this.map.getLayers().forEach(layer => {
        if (layer instanceof VectorLayer /*&& layer.getSource() instanceof Cluster*/) {
          this.map.removeLayer(layer);
        }
      });
    },
    removeLayer(name) {
      this.map.getLayers().forEach((layer) => {
        if (layer && (layer.get('name') === name)) {
          this.map.removeLayer(layer);
        }
      });
    },
    clearFilters() {
      this.statusesFilter = [];
      this.typesFilter = [];
      this.applyFilters();
      this.openMapFilters = false;
    },
    applyFilters() {
      this.openMapFilters = false;
      let statusIncluded = true;
      let typeIncluded = true;
      this.filteredDataPoints = this.dataPoints.filter(dataPoint => {
        if (this.typesFilter.length) {
          typeIncluded = this.typesFilter.some(filter => filter.id === dataPoint.dataPointType.id);
        }
        if (this.statusesFilter.length) {
          statusIncluded = this.statusesFilter.some(filter => filter.id === dataPoint.status.id);
        }
        return statusIncluded && typeIncluded;
      });
    },
    viewDataPoint() {
      this.$store.MobileDynamicTitle = this.$dataPoint_store.currentDataPoint.name
      this.$router.push('/view_data_point');
    },
    getLongLat(latLong) {
      return latLong ? latLong.split(',').reverse().map(Number) : [47.313220, -1.319482]
    }
  }
};
</script>

<style scoped>
.tracking-title {
  background: #ea0000;
  text-align: center;
  color: white;
  padding: 1px 15px;
  border-radius: 16px;
}

.tracking_container {
  position: fixed;
  top: 77px !important;
  z-index: 99 !important;
  background: transparent;
  text-align: center;
  width: 100%;
  display: block ruby;
}

.locate {
  bottom: 7.5em;
  right: .5em;
}

.card-text {
  overflow-y: auto;
}

.custom-bg {
  background-color: palegoldenrod;
}

.card-actions {
  position: absolute;
  bottom: 0;
  width: 100%;
  left: 0;
}

.red-bg {
  background: #ef0000;
}

.orange-bg {
  background: #ff9800;
}

.green-bg {
  background: #03b751;
}

.someExtraClass {
  color: black;
  font-weight: bold !important;
  padding: 4px;
  border-radius: 0 15px 15px 15px;
  text-align: center;
  width: auto !important;
  height: auto !important;
  margin: 0 !important;
  font-size: 10px;
}

.extra_actions {
  position: fixed;
  z-index: 999;
  bottom: 70px;
  left: 10px;
  border-radius: 50px;
  padding: 5px;
  display: flex;
  flex-direction: column;
  width: calc(100% - 20px);
}

.filter-btn {
  align-self: flex-start;
}


.vertical-buttons {
  position: fixed;
  z-index: 999;
  bottom: 70px;
  right: 10px;
  border-radius: 50px;
  padding: 5px;
  display: flex;
  flex-direction: column;
}
</style>
