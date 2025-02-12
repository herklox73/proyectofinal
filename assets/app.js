import { Application } from "stimulus";
import { definitionsFromContext } from "stimulus/webpack-helpers";

// Importa los controladores que vas a usar
import "bootstrap"; // Si estás utilizando Bootstrap o cualquier otro framework CSS

// Configura Stimulus
const application = Application.start();
const context = require.context("./controllers", true, /\.js$/);
application.load(definitionsFromContext(context));
