
  <!DOCTYPE html>
  <html lang="en">
  <body>
  <script src="https://unpkg.com/gojs@2.2.7/release/go.js"></script>
  
  <div id="allSampleContent" class="p-4 w-full">
  <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700" rel="stylesheet" type="text/css">
    <script id="code">
    function init() {

      // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
      // For details, see https://gojs.net/latest/intro/buildingObjects.html
      const $ = go.GraphObject.make;  // for conciseness in defining templates

      myDiagram =
        $(go.Diagram, "myDiagramDiv",  // must be the ID or reference to div
          {
            "toolManager.hoverDelay": 100,  // 100 milliseconds instead of the default 850
            allowCopy: false,
            layout:  // create a TreeLayout for the family tree
              $(go.TreeLayout,
                { angle: 90, nodeSpacing: 10, layerSpacing: 40, layerStyle: go.TreeLayout.LayerUniform })
          });

      var bluegrad = '#90CAF9';
      var pinkgrad = '#F48FB1';
      var ungu = '#E9D5DA';
      var biru = '#40DFEF';
      var orange = '#FF5F00';

      // Set up a Part as a legend, and place it directly on the diagram
      myDiagram.add(
        $(go.Part, "Table",
          { position: new go.Point(-200, 10), selectable: false },
          $(go.TextBlock, "List",
            { row: 0, font: "700 14px Droid Serif, sans-serif" }),  // end row 0
          $(go.Panel, "Horizontal",
            { row: 1, alignment: go.Spot.Left },
            $(go.Shape, "Rectangle",
              { desiredSize: new go.Size(30, 30), fill: bluegrad, margin: 5 }),
            $(go.TextBlock, "Direktur",
              { font: "700 13px Droid Serif, sans-serif" })
          ),  // end row 1
          $(go.Panel, "Horizontal",
            { row: 2, alignment: go.Spot.Left },
            $(go.Shape, "Rectangle",
              { desiredSize: new go.Size(30, 30), fill: pinkgrad, margin: 5 }),
            $(go.TextBlock, "General Manager",
              { font: "700 13px Droid Serif, sans-serif" })
          ),  // end row 2
          $(go.Panel, "Horizontal",
            { row: 3, alignment: go.Spot.Left },
            $(go.Shape, "Rectangle",
              { desiredSize: new go.Size(30, 30), fill: ungu, margin: 5 }),
            $(go.TextBlock, "Manager",
              { font: "700 13px Droid Serif, sans-serif" })
          ),  // end row 3
          $(go.Panel, "Horizontal",
            { row: 4, alignment: go.Spot.Left },
            $(go.Shape, "Rectangle",
              { desiredSize: new go.Size(30, 30), fill: biru, margin: 5 }),
            $(go.TextBlock, "Spv",
              { font: "700 13px Droid Serif, sans-serif" })
          ),  // end row 4
          $(go.Panel, "Horizontal",
            { row: 5, alignment: go.Spot.Left },
            $(go.Shape, "Rectangle",
              { desiredSize: new go.Size(30, 30), fill: orange, margin: 5 }),
            $(go.TextBlock, "Staff",
              { font: "700 13px Droid Serif, sans-serif" })
          )  // end row 5
        ));

      // get tooltip text from the object's data
      function tooltipTextConverter(person) {
        var str = "";
        str += "Born: " + person.birthYear;
        if (person.deathYear !== undefined) str += "\nDied: " + person.deathYear;
        if (person.reign !== undefined) str += "\nReign: " + person.reign;
        return str;
      }

      // define tooltips for nodes
      var tooltiptemplate =
        $("ToolTip",
          { "Border.fill": "whitesmoke", "Border.stroke": "black" },
          $(go.TextBlock,
            {
              font: "bold 8pt Helvetica, bold Arial, sans-serif",
              wrap: go.TextBlock.WrapFit,
              margin: 5
            },
            new go.Binding("text", "", tooltipTextConverter))
        );

      // define Converters to be used for Bindings
      function genderBrushConverter(gender) {
        if (gender === "M") return bluegrad;
        if (gender === "F") return pinkgrad;
        if (gender === "A") return bluegrad;
        if (gender === "B") return pinkgrad;
        if (gender === "C") return ungu;
        if (gender === "D") return biru;
        if (gender === "E") return orange;
        return "orange";
      }

      // replace the default Node template in the nodeTemplateMap
      myDiagram.nodeTemplate =
        $(go.Node, "Auto",
          { deletable: false, toolTip: tooltiptemplate },
          new go.Binding("text", "name"),
          $(go.Shape, "Rectangle",
            {
              fill: "lightgray",
              stroke: null, strokeWidth: 0,
              stretch: go.GraphObject.Fill,
              alignment: go.Spot.Center
            },
            new go.Binding("fill", "gender", genderBrushConverter)),
          $(go.TextBlock,
            {
              font: "700 12px Droid Serif, sans-serif",
              textAlign: "center",
              margin: 10, maxSize: new go.Size(80, NaN)
            },
            new go.Binding("text", "name"))
        );

      // define the Link template
      myDiagram.linkTemplate =
        $(go.Link,  // the whole link panel
          { routing: go.Link.Orthogonal, corner: 5, selectable: false },
          $(go.Shape, { strokeWidth: 3, stroke: '#424242' }));  // the gray link shape

      // here's the family data
      var nodeDataArray = [
        { key: 0, name: "Direktur", gender: "A", birthYear: "1865", deathYear: "", reign: "" },
        { key: 1, parent: 0, name: "Manager Audit", gender: "B", birthYear: "1894", deathYear: "", reign: "" },
        { key: 2, parent: 0, name: "GM Operasional", gender: "C", birthYear: "1894", deathYear: "", reign: "" },
        { key: 3, parent: 0, name: "GM Sales", gender: "C", birthYear: "1894", deathYear: "", reign: "" },
        { key: 4, parent: 0, name: "GM Fit", gender: "C", birthYear: "1894", deathYear: "", reign: "" },
        { key: 5, parent: 2, name: "Manager", gender: "D", birthYear: "1894", deathYear: "", reign: "" },
        { key: 6, parent: 3, name: "Manager", gender: "D", birthYear: "1894", deathYear: "", reign: "" },
        { key: 7, parent: 4, name: "Manager", gender: "D", birthYear: "1894", deathYear: "", reign: "" },
        { key: 8, parent: 4, name: "Manager", gender: "D", birthYear: "1894", deathYear: "", reign: "" },
        { key: 9, parent: 5, name: "Spv", gender: "E", birthYear: "1894", deathYear: "", reign: "" },
        { key: 10, parent: 5, name: "Staff", gender: "E", birthYear: "1894", deathYear: "", reign: "" },
        { key: 11, parent: 6, name: "Spv", gender: "E", birthYear: "1894", deathYear: "", reign: "" },
        { key: 12, parent: 6, name: "Staff", gender: "E", birthYear: "1894", deathYear: "", reign: "" },
        { key: 13, parent: 8, name: "Spv", gender: "E", birthYear: "1894", deathYear: "", reign: "" },
        { key: 14, parent: 8, name: "Staff", gender: "E", birthYear: "1894", deathYear: "", reign: "" },


        // { key: 2, parent: 0, name: "C", gender: "M", birthYear: "1895", deathYear: "1952", reign: "1936-1952" },
        // { key: 7, parent: 2, name: "Elizabeth II", gender: "F", birthYear: "1926", reign: "1952-" },
        // { key: 16, parent: 7, name: "Charles, Prince of Wales", gender: "M", birthYear: "1948" },
        // { key: 38, parent: 16, name: "Prince William", gender: "M", birthYear: "1982" },
        // { key: 39, parent: 16, name: "Prince Harry of Wales", gender: "M", birthYear: "1984" },
        // { key: 17, parent: 7, name: "Anne, Princess Royal", gender: "F", birthYear: "1950" },
        // { key: 40, parent: 17, name: "Peter Phillips", gender: "M", birthYear: "1977" },
        // { key: 82, parent: 40, name: "Savannah Phillips", gender: "F", birthYear: "2010" },
        // { key: 41, parent: 17, name: "Zara Phillips", gender: "F", birthYear: "1981" },
        // { key: 18, parent: 7, name: "Prince Andrew", gender: "M", birthYear: "1960" },
        // { key: 42, parent: 18, name: "Princess Beatrice of York", gender: "F", birthYear: "1988" },
        // { key: 43, parent: 18, name: "Princess Eugenie of York", gender: "F", birthYear: "1990" },
        // { key: 19, parent: 7, name: "Prince Edward", gender: "M", birthYear: "1964" },
        // { key: 44, parent: 19, name: "Lady Louise Windsor", gender: "F", birthYear: "2003" },
        // { key: 45, parent: 19, name: "James, Viscount Severn", gender: "M", birthYear: "2007" },
        // { key: 8, parent: 2, name: "Princess Margaret", gender: "F", birthYear: "1930", deathYear: "2002" }
      ];

      // create the model for the family tree
      myDiagram.model = new go.TreeModel(nodeDataArray);

      document.getElementById('zoomToFit').addEventListener('click', () => myDiagram.commandHandler.zoomToFit());

      document.getElementById('centerRoot').addEventListener('click', () => {
        myDiagram.scale = 1;
        myDiagram.scrollToRect(myDiagram.findNodeForKey(0).actualBounds);
      });

    }
    window.addEventListener('DOMContentLoaded', init);
  </script>

<div id="sample">
  <div id="myDiagramDiv" style="background-color: white; border: 1px solid black; width: 100%; height: 550px; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);"><canvas tabindex="0" width="1555" height="796" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 1037px; height: 531px;">This text is displayed if your browser does not support the Canvas HTML element.</canvas><div style="position: absolute; overflow: auto; width: 1037px; height: 548px; z-index: 1;"><div style="position: absolute; width: 4113.22px; height: 1px;"></div></div></div>
  <p><button id="zoomToFit">Zoom to Fit</button> <button id="centerRoot">Center on root</button></p>
  </div>
  </body>
  </html> 