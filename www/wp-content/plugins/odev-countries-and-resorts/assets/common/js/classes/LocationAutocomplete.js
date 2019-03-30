/**
 * Created by aleksandrfishchenko on 12.09.16.
 */

if ( typeof (window.application) === "undefined" ) {
    window.application = {};
}

(function ( $, application ) {

    function LocationAutocomplete( inputId, resultContainerId, submitSearchBtnId ) {
        this.$input = $( '#' + inputId );
        this.$submitSearchBtn = $( '#' + submitSearchBtnId );
        this.resultContainerId = resultContainerId;
        this.cache = {};

        this.initAutocomplete();
        this.$submitSearchBtn.on( 'click', $.proxy( this.submitSearch, this ) );
    }

    //INIT AUTOCOMPLETE AND LINK METHODS
    LocationAutocomplete.prototype.initAutocomplete = function () {
        $.widget( "custom.locautocomplete", $.ui.autocomplete, {
            _create: function () {
                this._super();
                this.widget().menu( "option", "items", "> :not(.location-category)" );
            },
            _renderItem: this.renderSuggestion,
            _renderMenu: this.renderSuggestions,
            _getSuggestionCatLabel: this.getSuggestionCatLabel,
        } );

        this.$input.locautocomplete( {
            delay: 400,
            minLength: 0,
            appendTo: '#' + this.resultContainerId,
            source: $.proxy( this.searchSuggestions, this ),
            select: $.proxy( function ( event, ui ) {
                event.preventDefault();
                if ( !ui.hasOwnProperty( 'item' ) && ui.item ) {
                    return;
                }
                var selectedLocation = ui.item;
                var targetUrl = ui.item.link;

                //GOTO PAGE;
                if ( targetUrl ) {
                    window.location.href = targetUrl;
                }
                else {
                    this.$input.val( '' );
                }
            }, this ),
            focus: $.proxy( function ( event, ui ) {
                event.preventDefault();
                if ( !ui.hasOwnProperty( 'item' ) || !ui.item ) {
                    return;
                }
                var locInput = this.$input;
                var selectedLocation = ui.item;
                locInput.attr( {
                    "data-value": selectedLocation.id,
                    "data-type": selectedLocation.type,
                    "data-target": selectedLocation.link
                } ).val( selectedLocation.label );
            }, this )
        } ).focus(function () {
            $(this).locautocomplete("search", this.value);
        });
    };

    //SEARCH FOR LOCATION SUGGESTION
    LocationAutocomplete.prototype.searchSuggestions = function ( request, responseCallback ) {
        if ( !request.hasOwnProperty( 'term' ) || typeof(window.wpAjaxUrl) === 'undefined' ) {
            responseCallback( [] );
            return;
        }

        if ( request.term in this.cache ) {
            responseCallback( this.cache[ request.term ] );
            return;
        }

        var self = this;
        // Enable loading state
        self.$input.parent().addClass('c-m_search-input-loading');

        var wpAjaxUrl = window.wpAjaxUrl;
        $.ajax( {
            url: wpAjaxUrl + '?action=catalog_search',
            method: "POST",
            data: {
                query: request.term
            },
            success: $.proxy( function ( responseData ) {
                // Disable loading state
                self.$input.parent().removeClass('c-m_search-input-loading');

                var inputSuggestions = responseData;
                var outputSuggestions = [];

                for ( var s in inputSuggestions ) {
                    if ( !inputSuggestions.hasOwnProperty( s ) ) {
                        continue;
                    }

                    var suggestion = inputSuggestions[ s ];
                    suggestion.value = suggestion.label;

                    outputSuggestions.push( suggestion );
                }
                this.cache[ request.term ] = outputSuggestions;
                responseCallback( outputSuggestions );
            }, this )
        } );
    };

    //RENDER SUGGESTIONS
    LocationAutocomplete.prototype.renderSuggestions = function ( list, suggestions ) {
        var that = this;
        var curSuggestionType
        $.each( suggestions, function ( index, suggestion ) {
            if ( suggestion.type !== curSuggestionType ) {
                list.append(
                    $( '<li>', { "class": "location-category" } ).text( that._getSuggestionCatLabel( suggestion.type ) )
                );
                curSuggestionType = suggestion.type;
            }
            that._renderItemData( list, suggestion );
        } );
    }

    //RENDER SUGGESTION
    LocationAutocomplete.prototype.renderSuggestion = function ( list, suggestion ) {
        var suggestionGeo = '';
        if ( suggestion.hasOwnProperty( 'geo' ) ) {
            suggestionGeo = ' - ' + suggestion.geo;
        }

        return $( "<li>", {
            "data-value": suggestion.value,
            "data-type": suggestion.type,
            "data-target": suggestion.link,
        } )
            .append( suggestion.label )
            .append( suggestionGeo )
            .appendTo( list );
    };

    //GET SUGGESTION CAT LABEL
    LocationAutocomplete.prototype.getSuggestionCatLabel = function ( type ) {
        var suggestionCatsLbls = {
            "country": "Страны",
            "resort": "Курорты",
            "hotel": "Отели"
        };
        if ( suggestionCatsLbls.hasOwnProperty( type ) ) {
            return suggestionCatsLbls[ type ];
        }
        return '';
    };

    //SUBMIT SEARCH BUTTON CLICK
    LocationAutocomplete.prototype.submitSearch = function ( e ) {
        var selectedTarget = this.$input.data( 'target' );
        if ( selectedTarget ) {
            window.location.href = selectedTarget;
        }
        else {
            this.$input.val( '' );
        }
        e.preventDefault();
    };

    application.LocationAutocomplete = LocationAutocomplete;

})( jQuery, window.application );
