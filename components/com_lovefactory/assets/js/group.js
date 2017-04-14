var lovefactory_member_requests = [];
var lovefactory_members_limit = 5;

function LoveFactoryGoogleMapGroup(group) {
    this._group = group;
    this._latlng = new GLatLng(group.lat, group.lng);
    this._members = group.members;
    this._noMembers = this._members.length;
}

LoveFactoryGoogleMapGroup.prototype = new GOverlay();

LoveFactoryGoogleMapGroup.prototype.initialize = function (map) {
    var container = document.createElement('div');

    container.className = 'lovefactory-group-container';

    if (this._noMembers == 1) {
        container.appendChild(this.addMember(this._group.label, this._members[0].user_id, 1, this._members[0].link));
    } else {
        container.appendChild(this.addGroup());
    }

    map.getPane(G_MAP_FLOAT_PANE).appendChild(container);

    this._map = map;
    this._container = container;
}

LoveFactoryGoogleMapGroup.prototype.addGroup = function () {
    var group = document.createElement('div');
    var label = document.createElement('div');
    var members = document.createElement('div');

    group.className = 'lovefactory-group';
    label.className = 'lovefactory-group-label';
    members.className = 'lovefactory-group-members';

    label.innerHTML = this._group.label;
    members.style.display = 'none';

    group.appendChild(label);

    // Create pagination
    var pagination = document.createElement('div');
    var prev = document.createElement('a');
    var next = document.createElement('a');
    var current_page = 1;
    var max_page = 1;

    pagination.className = 'lovefactory-group-pagination';
    prev.className = 'group-pagination-prev';
    next.className = 'group-pagination-next';

    prev.href = '#';
    next.href = '#';

    prev.rel = -1;
    next.rel = 1;

    pagination.appendChild(prev);
    pagination.appendChild(next);
    members.appendChild(pagination);

    for (var i = 0; i < this._noMembers; i++) {
        var member = this._members[i];
        var page = Math.ceil((i + 1) / lovefactory_members_limit);

        members.appendChild(this.addMember(member.username, member.user_id, page, member.link));
        max_page = page;
    }

    group.appendChild(members);

    prev.style.display = 'none';
    if (max_page == current_page) {
        next.style.display = 'none';
    }

    jQueryFactory(group).hover(function () {
        var object = jQueryFactory(this);

        object.find('.lovefactory-group-members').show();
        object.parents('.lovefactory-group-container:first').css('z-index', 1000);

    }, function () {
        var object = jQueryFactory(this);

        object.find('.lovefactory-group-members, .colorTip').hide();
        object.parents('.lovefactory-group-container:first').css('z-index', '');
    });

    jQueryFactory(pagination).find('a').click(function (event) {
        event.preventDefault();
        var direction = jQueryFactory(this).attr('rel');
        var group = jQueryFactory(members);
        var next_page = parseInt(current_page) + parseInt(direction);

        group.find('div[rel="' + parseInt(current_page) + '"]').hide();
        group.find('div[rel="' + parseInt(next_page) + '"]').show();

        current_page = next_page;

        prev.style.display = current_page > 1 ? 'block' : 'none';
        next.style.display = current_page != max_page ? 'block' : 'none';
    }).dblclick(function (event) {
        event.stopPropagation();
    });

    return group;
}

LoveFactoryGoogleMapGroup.prototype.addMember = function (username, user_id, page, link) {
    var member = document.createElement('div');

    member.className = 'lovefactory-member';
    member.innerHTML = username;
    member.id = user_id;
    member.setAttribute('rel', page);
    member.title = '<img src="components/com_lovefactory/assets/images/ajax-loader2.gif" />';

    if (page != 1) {
        member.style.display = 'none';
    }

    jQueryFactory(member)
        .colorTip({color: 'yellow', timeout: 500})
        .hover(function () {
            jQueryFactory(this).parents('.lovefactory-group-container:first').css('z-index', 1000);
        }, function () {
            jQueryFactory(this).parents('.lovefactory-group-container:first').css('z-index', '');
        })
        .click(function (event) {
            event.preventDefault();

            document.window.location.href = link;
        });

    return member;
}

LoveFactoryGoogleMapGroup.prototype.remove = function () {
    this._container.parentNode.removeChild(this._container);
}

LoveFactoryGoogleMapGroup.prototype.copy = function () {
    return new LoveFactoryGoogleMapGroup(this._group);
}

LoveFactoryGoogleMapGroup.prototype.redraw = function (force) {
    if (!force) {
        return;
    }

    var divPixel = this._map.fromLatLngToDivPixel(this._latlng);

    this._container.style.left = (divPixel.x - this._container.offsetWidth / 2) + 'px'
    this._container.style.top = (divPixel.y - this._container.offsetHeight / 2) + 'px';
}

LoveFactoryGoogleMapGroup.prototype.getLatLng = function () {
    return this._latlng;
}

LoveFactoryGoogleMapGroup.prototype.isHidden = function () {
    return false;
}
