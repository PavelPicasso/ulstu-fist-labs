jsPlumb.ready(function() {
    window.jsPlumbInstance = jsPlumb.getInstance({
        Container: ".container",
        Connector: "Straight",
        DragOptions: { cursor: 'pointer', zIndex: 2000 },
        Endpoint: ["Dot", {radius: 5}],
        ConnectionOverlays: [
            ["Arrow", {location: 1}],
            ["Custom", {
                create: function(component) {
                    var label = $("<input size='1'>");
                    label.on("keyup", function() {
                        this.style.width = ((this.value.length || 1) * 7) + 'px';
                    });

                    label.on("change", {component: component}, function(event) {
                        var sourceNumber = +$(".dot-number", event.data.component.source).text();
                        var targetNumber = +$(".dot-number", event.data.component.target).text();
                        model.setConnectionWeight(sourceNumber - 1, targetNumber - 1, +$(this).val());
                    });
                    label.on("keypress", function() {
                        return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 45;
                    });
                    label.keyup();
                    return label;
                },
                id: "weightInput"
            }]
        ]
    });

    jsPlumbInstance.bind("connectionDrag", function() {
        $(".jsplumb-endpoint").addClass("jsplumb-connection-in-progress");
    });

    jsPlumbInstance.bind("connection", function() {
        $(".jsplumb-endpoint").removeClass("jsplumb-connection-in-progress");
    });

    jsPlumbInstance.bind("connectionAborted", function() {
        $(".jsplumb-endpoint").removeClass("jsplumb-connection-in-progress");
    });

    jsPlumbInstance.bind("connectionDetached", function(connection) {
        model.setConnectionWeight($(connection.source).data("number") - 1, $(connection.target).data("number") - 1, Infinity);
        $(".jsplumb-endpoint").removeClass("jsplumb-connection-in-progress");
    });


});