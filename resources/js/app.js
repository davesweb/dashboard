import Collapse from "bootstrap/js/src/collapse";
import Dropdown from "bootstrap/js/src/dropdown";
import Tab from "bootstrap/js/src/tab";
import Sidebar from "./components/Sidebar";

let sidebar = window.sidebar = new Sidebar(document.getElementById('sidebar'), document.getElementById('main-content'));
sidebar.bindBootstrapEvents();