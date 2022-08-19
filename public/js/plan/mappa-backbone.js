function requestAnimFrame(b) {
    try {
        return window.requestAnimationFrame(b)
    } catch (a) {
        try {
            return window.webkitRequestAnimationFrame(b)
        } catch (a) {
            return window.mozRequestAnimationFrame(b)
        }
    }
}

function cancelAnimFrame(b) {
    try {
        return window.CancelAnimationFrame(b)
    } catch (a) {
        try {
            return window.webkitCancelAnimationFrame(b)
        } catch (a) {
            return window.cancelAnimationFrame(b)
        }
    }
}

function getOffsets(b) {
    var a = [];
    if (typeof(b.offsetX) != "undefined") {
        mx = b.offsetX;
        my = b.offsetY
    } else {
        mx = parseInt(b.clientX - $(b.target).offset().left);
        my = parseInt(b.pageY - $(b.target).offset().top) - 2
    }
    a.x = mx;
    a.y = my;
    return a
}
ImageMap = Backbone.RelationalModel.extend({
    relations: [{
        type: Backbone.HasMany,
        key: "areas",
        relatedModel: "Area",
        reverseRelation: {
            key: "mappa"
        },
        collectionType: "Areas"
    }],
    subModelTypes: {
        polygon: "Polygon",
        rect: "Rectangle",
        circle: "Circle"
    },
    subModelTypeAttribute: "shape",
    defaults: {
        name: "imagemap"
    },
    getMapTag: function() {
        var a = "";
        a += "<% _.each(areas, function(a) { %><%= a %><% }); %>";
        return _.template(a, this.getViewModel())
    },
    getMapHTMLTag: function() {
        var a = "";
        a += "<% _.each(areas, function(a) { %><%= a %><% }); %>";
        return _.template(a, this.getHTMLViewModel())
    },
    getViewModel: function() {
        var a = {
            name: this.get("name")
        };
        a.areas = this.get("areas").map(function(b) {
            return b.getAreaTag()
        });
        return a
    },
    getHTMLViewModel: function() {
        var a = {
            name: this.get("name")
        };
        a.areas = this.get("areas").map(function(b) {
            return b.getHTMLAreaTag()
        });
        return a
    }
});
Area = Backbone.RelationalModel.extend({
    relations: [{
        type: Backbone.HasMany,
        key: "points",
        relatedModel: "Point",
        collectionType: "Points",
        reverseRelation: {
            key: "area"
        }
    }],
    defaults: {
        selected: false,
        shape: null,
        href: null,
        alt: null,
        attrs: []
    },
    updateOffset: function(a, d) {
        var c = a - this.offsetX,
            b = d - this.offsetY;
        this.get("points").forEach(function(e) {
            e.set("x", e.get("x") + c);
            e.set("y", e.get("y") + b)
        });
        this.offsetX = a;
        this.offsetY = d
    },
    getAreaTag: function() {
        var a = "";
        model = this.getViewModel();
        a += "{";
        a += '"shape": "<%=shape%>",';
        a += '"selected": false,';
        a += '"points":[';
        a += "<%=coords%>";
        a += "]";
        a += "},\n";
        return _.template(a, model)
    },
    getHTMLAreaTag: function() {
        var b = "",
            a = this.getHTMLViewModel();
        b += " <area\n";
        b += ' shape="<%=shape%>"\n';
        b += ' href=""\n';
        b += ' alt="<%=alt%>"\n';
        b += ' coords="<%=coords%>"\n';
        b += '<% _.each(attrs, function(a) { %> <%= a.key %>="<%= a.value %>"\n<% }); %>';
        b += " />\n";
        return _.template(b, a)
    },
    getCoords: function() {
        return this.get("points").reduce(function(a, e, b, c) {
            var d = a + e.get("x") + "," + e.get("y");
            if (b < c.length - 1) {
                d += ","
            }
            return d
        }, "")
    },
    getHTMLViewModel: function() {
        var a = {
            shape: this.get("shape"),
            href: this.get("floor"),
            alt: this.get("alt"),
            attrs: this.get("attrs"),
            coords: this.getCoords()
        };
        return a
    },
    getViewModel: function() {
        var d = "";
        var c = this.getCoords();
        var b = c.split(",");
        for (i = 0; i < b.length - 1; i++) {
            if (i % 2 == 0) {
                d += '{"x":' + b[i] + ',"y":' + b[i + 1] + "}";
                j = i - 2;
                if (j < i) {
                    d += ","
                }
            }
        }
        var a = {
            shape: this.get("shape"),
            href: this.get("floor"),
            alt: this.get("alt"),
            attrs: this.get("attrs"),
            coords: d
        };
        return a
    },
    deleteMousedOverPoint: function(f, e, c, d) {
        var b = this.isMousedOverPoint;
        var a = this.get("points").find(function(g) {
            return b(f, e, g, c, d)
        });
        if (a) {
            this.get("points").remove(a)
        }
        if (this.get("points").length === 0 && this.collection && this.collection.remove) {
            this.collection.remove(this)
        }
    },
    isMousedOverPoint: function(f, e, b, c, d) {
        var a, g;
        if (b.x) {
            a = b.x;
            g = b.y
        } else {
            if (b.get) {
                a = b.get("x");
                g = b.get("y")
            } else {
                return false
            }
        }
        return (f >= a - c / 2 && f <= a + c / 2 && e >= g - d / 2 && e <= g + d / 2)
    },
    isMousedOver: function(g, f) {
        var c, b, e = false;
        var d = this.get("points").toJSON();
        for (c = 0, b = d.length - 1; c < d.length; b = c++) {
            if (((d[c].y > f) != (d[b].y > f)) && (g < (d[b].x - d[c].x) * (f - d[c].y) / (d[b].y - d[c].y) + d[c].x)) {
                e = !e
            }
        }
        if (!e) {
            var a = this.isMousedOverPoint;
            d.forEach(function(h) {
                if (a(g, f, h, 5, 5)) {
                    e = true
                }
            })
        }
        return e
    }
});
Areas = Backbone.Collection.extend({
    model: Area
});
Polygon = Area.extend({
    defaults: {
        shape: "polygon"
    }
});
Rectangle = Area.extend({
    defaults: {
        shape: "rect"
    }
});
Circle = Area.extend({
    defaults: {
        shape: "circle",
        radius: null
    },
    getCoords: function() {
        var a = Area.prototype.getCoords.apply(this);
        a += "," + this.get("radius");
        return a
    }
});
Point = Backbone.RelationalModel.extend({
    defaults: {
        x: null,
        y: null
    }
});
Points = Backbone.Collection.extend({
    model: Point,
    getPoints: function() {
        return this.models.map(function(a) {
            return {
                x: a.get("x"),
                y: a.get("y")
            }
        })
    }
});
var TOOL_ARROW = "arrow";
var TOOL_DELETE = "delete";
var TOOL_POLYGON = "polygon";
MapView = Backbone.View.extend({
    events: {
        "mousemove canvas": "mouseMove",
        "mousedown canvas": "mouseDown",
        "mouseup canvas": "mouseUp",
        dragover: "dragOver",
        dragenter: "dragOver",
        "change input": "changeTool",
        "click .selected-true input": "deleteArea",
        "change .mappa-list select": "updateAttribute",
        "paste textarea": "pasteImageTag"
    },
    pasteImageTag: function(a) {
        this.parseImageTag(a.target.value)
    },
    parseImageTag: function(a) {
        alert("Sorry - pasting not supported yet")
    },
    initialize: function() {
        _.bindAll(this, "onLoadImage", "renderArea", "rafRender", "createAreaView", "clearCanvas", "drop");
        this.mouseMove = _.throttle(this.mouseMove, 1000 / 60);
        this.current_tool = TOOL_ARROW;
        this.model = new ImageMap(map);
        this.canvas = this.el.querySelector("canvas");
        this.context = this.canvas.getContext("2d");
        this.createAreaViews();
        this.el.addEventListener("drop", this.drop)
    },
    deleteArea: function(b) {
        var a = parseInt(b.target.parentNode.getAttribute("data-index"), 10);
        this.model.get("areas").at(a).destroy();
        this.render()
    },
    dragOver: function(a) {
        a.preventDefault();
        this.$el.addClass("hover");
        return false
    },
    dragEnter: function(a) {
        a.preventDefault();
        this.$el.addClass("hover");
        return false
    },
    dragEnd: function(a) {
        this.$el.removeClass("hover");
        return false
    },
    drop: function(c) {
        this.$el.removeClass("hover");
        c.preventDefault();
        if (!c.dataTransfer.files.length) {
            return
        }
        var a = ["image/jpg", "image/png", "image/gif", "image/webm"];
        if (a.indexOf(c.dataTransfer.files[0].type) === -1) {
            return
        }
        var d = new FileReader();
        var b = this.image;
        d.onload = function(f) {
            b.src = f.target.result
        };
        d.readAsDataURL(c.dataTransfer.files[0]);
        return false
    },
    updateAttribute: function(c) {
        var a = c.target;
        var b = parseInt(a.parentNode.parentNode.getAttribute("data-index"), 10);
        this.model.get("areas").at(b).set(a.getAttribute("name"), a.value);
        this.render(true)
    },
    updateHTML: function() {
        var b = this.model.getMapTag();
        var a = this.model.getMapHTMLTag();
        this.$("textarea.mappa-html").val(b);
        this.$("textarea.mappa-area").val(a);
        if (!this.dont_render_list) {
            this.$(".mappa-list").html(this.getList())
        }
    },
    getList: function() {
        var b = "";
        b += "<% _(areas).each(function(area, index) { %>";
        b += '<li data-index="<%= index %>" class="selected-<%= area.selected %>">';
        b += '<input id="deleteArea" class="delete input_hidden" type="radio" value="deleteArea" name="tool"><label class="actionBtn tip deletePoint" for="deleteArea" title="Usuń element">Usuń element</label>';
        b += "</li>";
        b += "<% }); %>";
        var a = _.template(b, this.model.toJSON());
        return a
    },
    onLoadImage: function() {
        this.canvas.width = this.image.width;
        this.canvas.height = this.image.height;
        this.render()
    },
    loadImage: function(a) {
        this.image_url = a;
        this.image = new Image();
        this.image.onload = this.onLoadImage;
        this.image.src = a
    },
    createAreaViews: function() {
        if (!this.area_views) {
            this.area_views = []
        }
        this.model.get("areas").forEach(this.createAreaView)
    },
    createAreaView: function(b) {
        var a;
        switch (b.get("shape")) {
            case "polygon":
                a = new PolygonView({
                    model: b
                });
                break;
            case "rect":
                a = new RectangleView({
                    model: b
                });
                break;
            case "circle":
                a = new CircleView({
                    model: b
                });
                break
        }
        this.area_views.push(a)
    },
    render: function(a) {
        this.dont_render_list = a;
        if (this.rafId) {
            cancelAnimFrame(this.rafId)
        }
        this.rafId = requestAnimFrame(this.rafRender)
    },
    rafRender: function() {
        this.updateHTML();
        this.clearCanvas();
        this.renderImage();
        this.renderAreas();
        this.renderPoints()
    },
    clearCanvas: function() {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height)
    },
    renderImage: function() {
        this.context.drawImage(this.image, 0, 0)
    },
    renderAreas: function() {
        var a = this;
        this.area_views.forEach(this.renderArea)
    },
    renderArea: function(a) {
        a.render(this.context)
    },
    renderPoints: function() {
        var a = this.context;
        this.area_views.forEach(function(b) {
            if (b.model.get("selected") || b.model.highlighted) {
                b.model.get("points").forEach(function(d, f, k) {
                    if (f === 0) {
                        a.fillStyle = "#f00"
                    } else {
                        if (f === k.length - 1) {
                            a.fillStyle = "#000"
                        } else {
                            a.fillStyle = "#0ff"
                        }
                    }
                    var e = 5,
                        g = 5,
                        c = d.get("x") - e / 2,
                        l = d.get("y") - g / 2;
                    a.fillRect(c, l, e, g);
                    a.closePath()
                })
            }
        })
    },
    mouseMove: function(c) {
        var b = this;
        var a = getOffsets(c);
        c.offsetX = a.x;
        c.offsetY = a.y;
        if (this.moving_point) {
            this.moving_point.set("x", c.offsetX);
            this.moving_point.set("y", c.offsetY)
        } else {
            if (this.moving_view) {
                this.moving_view.model.updateOffset(c.offsetX, c.offsetY)
            }
        }
        this.area_views.forEach(function(d, e) {
            var f = d.model.isMousedOver(c.offsetX, c.offsetY);
            d.model.highlighted = f
        });
        this.render()
    },
    mouseDown: function(f) {
        var b = this,
            c, h;
        var a = getOffsets(f);
        var g = a.x;
        var d = a.y;
        if (this.current_tool === TOOL_ARROW) {
            this.area_views.forEach(function(e, k) {
                e.model.get("points").forEach(function(m) {
                    if (e.model.isMousedOverPoint(g, d, m, 5, 5)) {
                        c = e;
                        h = m
                    }
                });
                if (!b.moving_view) {
                    var l = e.model.isMousedOver(g, d);
                    e.model.set("selected", l);
                    if (l) {
                        c = e;
                        e.model.offsetX = g;
                        e.model.offsetY = d
                    }
                }
            });
            b.moving_view = c;
            b.moving_point = h;
            f.target.style.cursor = "move";
            this.render()
        }
        return false
    },
    mouseUp: function(d) {
        var a = getOffsets(d);
        d.offsetX = a.x;
        d.offsetY = a.y;
        if (this.adding_view && this.current_tool === TOOL_POLYGON) {
            this.adding_view.model.get("points").add({
                x: d.offsetX,
                y: d.offsetY
            });
            this.render()
        } else {
            if (this.current_tool === TOOL_POLYGON) {
                var c = new Polygon({
                    points: [{
                        x: d.offsetX,
                        y: d.offsetY
                    }],
                    selected: true
                });
                var b = new PolygonView({
                    model: c
                });
                this.model.get("areas").add(c);
                this.area_views.push(b);
                this.adding_view = b;
                this.render()
            } else {
                if (this.adding_view && this.current_tool === TOOL_DELETE) {
                    this.adding_view.model.deleteMousedOverPoint(d.offsetX, d.offsetY, 5, 5);
                    this.render()
                } else {
                    this.adding_view = this.moving_view
                }
            }
        }
        this.moving_view = null;
        this.moving_point = null;
        d.target.style.cursor = ""
    },
    changeTool: function(a) {
        if (a.target.checked) {
            this.current_tool = a.target.value
        }
    }
});
AreaView = Backbone.View.extend({
    contextAttributesUnselected: {
        fillStyle: "rgba(0, 255, 255, 0.6)",
        strokeStyle: "rgba(0, 255, 255, 0)",
        lineWidth: 0
    },
    contextAttributesSelected: {
        fillStyle: "rgba(0, 255, 255, 0.9)",
        strokeStyle: "#000",
        lineWidth: 1
    },
    setupContext: function(a) {
        var b = (this.model.get("selected")) ? this.contextAttributesSelected : this.contextAttributesUnselected;
        this.applyContextAttributes(a, b);
        return b
    },
    applyContextAttributes: function(b, a) {
        _(a).each(function(d, c) {
            b[c] = d
        })
    }
});
PolygonView = AreaView.extend({
    render: function(a) {
        var b = this.model.get("points").getPoints();
        var c = this.setupContext(a);
        if (!b[0]) {
            return
        }
        a.moveTo(b[0].x, b[0].y);
        a.beginPath();
        b.forEach(function(d, e) {
            if (!e) {
                return
            }
            a.lineTo(d.x, d.y)
        });
        a.lineTo(b[0].x, b[0].y);
        a.closePath();
        a.fill();
        a.stroke()
    }
});