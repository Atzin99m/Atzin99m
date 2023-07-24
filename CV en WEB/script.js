document.addEventListener('DOMContentLoaded', function() {
    var pdfURL = 'CV en WEB\CV.pdf'; // Reemplaza con la URL o ruta del archivo PDF

    pdfjsLib.getDocument(pdfURL).promise.then(function(pdf) {
        var numPages = pdf.numPages;
        var menu = document.getElementById('menu');

        for (var i = 1; i <= numPages; i++) {
            menu.innerHTML += '<li><a href="#" onclick="loadPage(' + i + ')">Página ' + i + '</a></li>';
        }
    });

    function loadPage(pageNum) {
        pdfjsLib.getDocument(pdfURL).promise.then(function(pdf) {
            pdf.getPage(pageNum).then(function(page) {
                var canvas = document.getElementById('pdfViewer');
                var context = canvas.getContext('2d');

                var viewport = page.getViewport({ scale: 1.5 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };

                page.render(renderContext);
            });
        });
    }
});
