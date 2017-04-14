// Extend class function
function extend(subclass, superclass) {
    function Dummy() {
    }

    Dummy.prototype = superclass.prototype;
    subclass.prototype = new Dummy();
    subclass.prototype.constructor = subclass;
    subclass.superclass = superclass;
    subclass.superproto = superclass.prototype;
}

// Default marker declaration
LFDefaultMarker.prototype = new google.maps.OverlayView();

LFDefaultMarker.prototype.onAdd = function () {
    this.div_ = document.createElement('div');
    this.getPanes().overlayMouseTarget.appendChild(this.div_);
}

LFDefaultMarker.prototype.draw = function () {
    var projection = this.getProjection(),
        div = this.div_,
        sw = projection.fromLatLngToDivPixel(this.bounds_.getSouthWest()),
        ne = projection.fromLatLngToDivPixel(this.bounds_.getNorthEast()),
        height = jQuery(div).outerHeight(),
        width = jQuery(div).outerWidth();

    div.style.left = (sw.x - width / 2) + 'px';
    div.style.top = (ne.y - height / 2) + 'px';
}

LFDefaultMarker.prototype.getPosition = function () {
    return this.bounds_.getCenter();
}

LFDefaultMarker.prototype.onRemove = function () {
    this.div_.parentNode.removeChild(this.div_);

    this.div_ = null;
}

function LFDefaultMarker(data, map) {
    var point = new google.maps.LatLng(data.lat, data.lng);

    this.bounds_ = new google.maps.LatLngBounds(point, point);
    this.map_ = map;
    this.div_ = null;
    this.data_ = data;

    this.setMap(map);
}


// Single link marker declaration
function LFSingleLinkMarker(data, map) {
    LFSingleLinkMarker.superclass.call(this, data, map);
};

extend(LFSingleLinkMarker, LFDefaultMarker);

LFSingleLinkMarker.prototype.onAdd = function () {
    var data = this.data_,
        div = document.createElement('div'),
        target = LoveFactory.get('profileLinkNewWindow') ? 'target="_blank"' : '';

    div.className = 'google-maps-marker google-maps-marker-user has-profile';
    div.innerHTML = '<i class="factory-icon icon-user"></i><a href="' + data.link + '" ' + target + ' rel="' + data.user_id + '">' + data.label + '</a>';

    this.div_ = div;

    this.getPanes().overlayMouseTarget.appendChild(this.div_);
}


// Multiple link marker declaration
function LFMultipleLinkMarker(data, map) {
    LFMultipleLinkMarker.superclass.call(this, data, map);
};

extend(LFMultipleLinkMarker, LFDefaultMarker);

LFMultipleLinkMarker.prototype.onAdd = function () {
    var data = this.data_,
        div = document.createElement('div'),
        target = LoveFactory.get('profileLinkNewWindow') ? 'target="_blank"' : '';

    div.className = 'google-maps-marker google-maps-marker-user';
    div.innerHTML = '<i class="factory-icon icon-user"></i><a data-rel="multiple.link" href="' + data.link + '" ' + target + '>' + data.label + '</a>';

    this.div_ = div;

    this.getPanes().overlayMouseTarget.appendChild(this.div_);
}


// Multiple group marker declaration
function LFMultipleGroupMarker(data, map) {
    LFMultipleGroupMarker.superclass.call(this, data, map);
};

extend(LFMultipleGroupMarker, LFDefaultMarker);

LFMultipleGroupMarker.prototype.onAdd = function () {
    var data = this.data_,
        div = document.createElement('div'),
        label = document.createElement('div'),
        container = document.createElement('div'),
        target = LoveFactory.get('profileLinkNewWindow') ? 'target="_blank"' : '',
        limit = 5;

    div.className = 'google-maps-marker';
    div.style.backgroundColor = '#cccccc';

    label.className = 'google-maps-marker-user';
    label.innerHTML = '<i class="factory-icon icon-user"></i>' + data.label;

    container.className = 'google-maps-markers-container';
    container.style.display = 'none';
    container.setAttribute('data-pages', Math.floor(data.count / limit));
    container.setAttribute('data-page', 0);

    for (var i = 0; i < data.count; i++) {
        var memberContainer = document.createElement('div'),
            member = data.members[i],
            page = Math.floor(i / limit);

        memberContainer.className = 'google-maps-marker-user has-profile';
        memberContainer.style.position = 'relative';
        memberContainer.innerHTML = '<i class="factory-icon icon-user"></i><a href="' + member.link + '" ' + target + ' rel="' + member.user_id + '">' + member.label + '</a>';
        memberContainer.setAttribute('data-page', page);

        if (page > 0) {
            memberContainer.style.display = 'none';
        }

        container.appendChild(memberContainer);
    }

    if (data.count > limit) {
        var pagination = document.createElement('div');

        pagination.className = 'google-maps-marker-pagination';
        pagination.innerHTML = '<a href="#" data-rel="pagination-prev" style="display: none;">' + Joomla.JText._('JPREVIOUS') + '</a><a href="#" data-rel="pagination-next">' + Joomla.JText._('JNEXT') + '</a>';

        container.insertBefore(pagination, container.firstChild);
    }

    div.appendChild(container);

    jQuery(div).hover(function (event) {
        jQuery(container).show();
        jQuery(label).hide();
    }, function (event) {
        jQuery(container).hide();
        jQuery(label).show();
    });

    div.appendChild(label);

    this.div_ = div;
    this.getPanes().overlayMouseTarget.appendChild(this.div_);
}


var path = 'components/com_lovefactory/assets/images/';
var LoveFactoryGoogleMapsPin = {
    icon: new google.maps.MarkerImage(
        path + 'pin.png',
        new google.maps.Size(32.0, 32.0),
        new google.maps.Point(0, 0),
        new google.maps.Point(2.0, 31.0)
    ),
    shadow: new google.maps.MarkerImage(
        path + 'shadow.png',
        new google.maps.Size(49.0, 32.0),
        new google.maps.Point(0, 0),
        new google.maps.Point(7.0, 30.0)
    )
}
var request = null;

jQuery(document).ready(function ($) {
    // Show member map profile.
    // $('.google-maps-marker-user.has-profile').live(LoveFactory.get('showProfileMouseEvent'), function (event) {
    $(document).on(LoveFactory.get('showProfileMouseEvent'), '.google-maps-marker-user.has-profile', function (event) {
        var elem = $(this),
            user_id = elem.find('a:first').attr('rel'),
            username = elem.find('a:first').html();

        showMemberMapProfile(user_id, username);

        event.preventDefault();
    });

    function showMemberMapProfile(user_id, username) {
        if (!user_id) {
            return false;
        }

        var dialog = LoveFactory.get('dialog', null);

        if (null == dialog) {
            dialog = $('<div style="padding: 10px;"></div>').dialog();
            dialog.parent().wrap('<div class="lovefactory-object" />');
            LoveFactory.set('dialog', dialog);
        }

        dialog.dialog('open').dialog({title: username});
        dialog.html('<i class="factory-icon icon-loader"></i>');

        var profile = LoveFactory.get('cache_profile_' + user_id, null);

        if (null != profile) {
            dialog.html(profile);
        } else {
            if (null != request) {
                request.abort();
            }

            request = $.get(LoveFactory.get('profileInfoRoute'), {user_id: user_id}, function (response) {
                dialog.html(response);
                LoveFactory.set('cache_profile_' + user_id, response);
            });
        }
    }

    // Pagination links.
    $(document).on('click', 'a[data-rel^="pagination-"]', function (event) {
        event.preventDefault();

        var $element = $(this),
            container = $element.parents('.google-maps-markers-container:first'),
            pages = container.data('pages'),
            page = container.data('page'),
            direction = $element.data('rel') == 'pagination-prev' ? -1 : 1,
            newPage = page + direction,
            prevLink = container.find('a[data-rel="pagination-prev"]'),
            nextLink = container.find('a[data-rel="pagination-next"]');

        if (newPage <= pages && newPage >= 0) {
            container
                .find('.google-maps-marker-user').hide().end()
                .find('div[data-page="' + (page + direction) + '"]').show().end()
                .data('page', newPage);

            if (newPage > 0) {
                prevLink.show();
            } else {
                prevLink.hide();
            }

            if (newPage < pages) {
                nextLink.show();
            } else {
                nextLink.hide();
            }
        }
    });
});
