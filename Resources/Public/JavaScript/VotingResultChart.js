// short-hand version
(function($) {
	$(function() {

		"use strict";

		$(document).on('click', '.content-box-expandable', function(e) {


				/**
				 * Referendum: population case
				 */
				if ($('.graph-referendum', this) && !$('.graph-referendum', this).html()) {

					$('.graph-referendum', this).each(function(index, el) {
						var yes = $(el).data('value');
						var no = 100 - yes;

						var configuration = getCommonPieChartConfiguration();

						configuration.data.content = [
							{
								"label": EasyvoteGraph.Label.no,
								"value": no,
								"color": "#D01762"
							},
							{
								"label": EasyvoteGraph.Label.yes,
								"value": yes,
								"color": "#13A3D5"
							}
						];

						configuration.labels.inner = {
							format: "percentage"
						};

						var pie = new d3pie(el, configuration);
					});
				}

				/**
				 * Initiative: canton case
				 */
				if ($('.graph-canton', this).length > 0 && !$('.graph-canton', this).html()) {

					var yes = $('.graph-canton', this).data('value');
					var no = 26 - yes;

					var configuration = getCommonPieChartConfiguration();


					configuration.data.content = [
						{
							"label": EasyvoteGraph.Label.no,
							"value": no,
							"color": "#D01762"
						},
						{
							"label": EasyvoteGraph.Label.yes,
							"value": yes,
							"color": "#13A3D5"
						}
					];

					configuration.labels.inner = {
						format: "value"
					};
					var pie = new d3pie($('.graph-canton', this).get(0), configuration);
				}


				/**
				 * Counter referendum: tie-break question
				 */
				if ($('.graph-tiebreak', this).length > 0 && !$('.graph-tiebreak', this).html()) {

					var yes = $('.graph-tiebreak', this).data('value');
					var no = 100 - yes;

					var configuration = getCommonPieChartConfiguration();


					var label1;
					var label2;
					if (yes > 50) {
						label1 = EasyvoteGraph.Label.counterProposalAbbreviated;
						label2 = EasyvoteGraph.Label.proposalAbbreviated;
					} else {
						label1 = EasyvoteGraph.Label.proposalAbbreviated;
						label2 = EasyvoteGraph.Label.counterProposalAbbreviated;
					}
					configuration.data.content = [
						{
							"label": label1,
							"value": no,
							"color": "#D01762"
						},
						{
							"label": label2,
							"value": yes,
							"color": "#13A3D5"
						}
					];

					configuration.size.canvasWidth = 250;
					configuration.labels.outer.pieDistance = 0;

					configuration.labels.inner = {
						format: "percentage"
					};
					var pie = new d3pie($('.graph-tiebreak', this).get(0), configuration);
				}
			}
		);

		/**
		 * @returns {{}}
		 */
		function getCommonPieChartConfiguration() {
			return {
				"size": {
					"canvasWidth": 220,
					"canvasHeight": 180,
					"pieOuterRadius": "85%"
				},
				"data": {
					"sortOrder": "none"
				},
				"labels": {
					"outer": {
						"pieDistance": 5
					},
					"mainLabel": {
						"fontSize": 11
					},
					"percentage": {
						"color": "#ffffff",
						"decimalPlaces": 2
					},
					"value": {
						"color": "#fff",
						"fontSize": 11
					},
					"lines": {
						"enabled": true
					},
					"truncation": {
						"enabled": true
					}
				},
				"effects": {
					"pullOutSegmentOnClick": {
						"effect": "none",
						"speed": 400,
						"size": 8
					},
					"load": {
						"effect": "none"
					}
				},
				"misc": {
					"canvasPadding": {
						"top": 0,
						"right": 0,
						"bottom": 0,
						"left": 0
					}
				}
			}
		}
	});
})(jQuery);

