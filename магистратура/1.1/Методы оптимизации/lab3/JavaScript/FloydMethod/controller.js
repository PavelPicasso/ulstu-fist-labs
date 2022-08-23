var dotAmount = 0;

$(document).ready(function() {
    var preventNonDigits = function(event) {
        return event.charCode >= 48 && event.charCode <= 57;
    };
    $("#firstTop").on("keypress", preventNonDigits);
    $("#secondTop").on("keypress", preventNonDigits);
});

function addDot() {
    var newDot = $("<div class='connectorHolder' data-number='" + ++dotAmount + "'><div class='dot-number'>" + dotAmount + "</div></div>");
    $(".container").append(newDot);

    addNewEndpoints(newDot);

    newDot.on("mouseover", function() {
        $(".jsplumb-endpoint").addClass("jsplumb-hover");
    });

    newDot.on("mouseout", function() {
        $(".jsplumb-endpoint").removeClass("jsplumb-hover");
    });

    jsPlumbInstance.draggable(newDot, {
        containment:true
    });

    window.model.addDot();
}

function addNewEndpoints(item) {
    var endpointCommonOptions = {
        isSource: true,
        isTarget: true
    };
    jsPlumbInstance.addEndpoint(item, {anchor: "Top"}, endpointCommonOptions);
    jsPlumbInstance.addEndpoint(item, {anchor: "Bottom"}, endpointCommonOptions);
    jsPlumbInstance.addEndpoint(item, {anchor: "Left"}, endpointCommonOptions);
    jsPlumbInstance.addEndpoint(item, {anchor: "Right"}, endpointCommonOptions);
}

function getDotByNumber(number) {
    return $("[data-number=" + number + "]")[0];
}

function getWeightOverlay(source, target) {
    return jsPlumbInstance.getConnections({source: source, target: target})[0].getOverlay("weightInput");
}

function showShortestPath(pathes, begin, end) {
    var shortestPath = [getDotByNumber(end)];

    (function getNextDot(begin, end) {
        if (begin != end) {
            var number = pathes[begin - 1][end - 1];
            var nextDot = getDotByNumber(number);
            shortestPath.push(getWeightOverlay(nextDot, shortestPath[shortestPath.length - 1]).getElement());
            shortestPath.push(nextDot);
            getNextDot(begin, number);
        }
    })(begin, end);

    (function highlightPath(shortestPath) {
        if (shortestPath.length > 0) {
            setTimeout(function() {
                $(shortestPath.pop()).addClass("highlighted");
                highlightPath(shortestPath);
            }, 700);
        }
    })(shortestPath);

}

function getShortestPath(pathes, begin, end) {
    var shortestPath = [end];

    (function getNextDot(begin, end) {
        if (begin != end) {
            var number = pathes[begin - 1][end - 1];
            shortestPath.push(number);
            getNextDot(begin, number);
        }
    })(begin, end);

    return shortestPath.reverse();
}

function arrayToString(array) {
    var result = "";
    $.each(array, function(i, row) {
        $.each(row, function(j, element) {
            result += new Array(9 - element.toString().length).fill(" ").join("") + element;
        });
        result += "\n";
    });
    return result;
}
