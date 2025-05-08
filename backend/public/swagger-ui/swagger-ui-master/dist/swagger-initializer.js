window.onload = function() {
  const ui = SwaggerUIBundle({
    url: "http://localhost/EmirGasi/Project-Introduction-to-Web-Programming/backend/swagger.json", // Update this with the correct path
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIBundle.presets.auth
    ],
    layout: "BaseLayout"
  });

  window.ui = ui;
};
