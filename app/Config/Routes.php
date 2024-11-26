<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->add('default', 'Default::index');
$routes->add('especeList', 'Espece::list');
$routes->add('especeDisplay', 'Espece::display');
$routes->add('especeChange', 'Espece::change');
$routes->post('especeWrite', 'Espece::write');
$routes->post('especeDelete', 'Espece::delete');
$routes->add('especeGetValues', 'Espece::getValues');
$routes->add('especeSearch', 'Espece::search');
$routes->add('especeExport', 'Espece::export');
$routes->add('materielList', 'Materiel::list');
$routes->add('materielChange', 'Materiel::change');
$routes->post('materielWrite', 'Materiel::write');
$routes->post('materielDelete', 'Materiel::delete');
$routes->add('materielExport', 'Materiel::export');
$routes->add('experimentationList', 'Experimentation::list');
$routes->add('experimentationChange', 'Experimentation::change');
$routes->post('experimentationWrite', 'Experimentation::write');
$routes->post('experimentationDelete', 'Experimentation::delete');
$routes->add('campagneList', 'Campagne::list');
$routes->add('campagneChange', 'Campagne::change');
$routes->post('campagneWrite', 'Campagne::write');
$routes->post('campagneDelete', 'Campagne::delete');
$routes->add('campagneDuplicate', 'Campagne::duplicate');
$routes->add('personneList', 'Personne::list');
$routes->add('personneChange', 'Personne::change');
$routes->post('personneWrite', 'Personne::write');
$routes->post('personneDelete', 'Personne::delete');
$routes->add('traitList', 'Traits::list');
$routes->add('traitDisplay', 'Traits::display');
$routes->add('traitChange', 'Traits::change');
$routes->post('traitWrite', 'Traits::write');
$routes->post('traitDelete', 'Traits::delete');
$routes->add('traitExport', 'Traits::export');
$routes->add('campagneSearchTrait', 'Campagne::searchTrait');
$routes->add('echantillonChange', 'Echantillon::change');
$routes->post('echantillonWrite', 'Echantillon::write');
$routes->post('echantillonDelete', 'Echantillon::delete');
$routes->add('echantillonExport', 'Echantillon::export');
$routes->post('individuWrite', 'Individu::write');
$routes->post('individuDelete', 'Individu::delete');
$routes->add('individuExport', 'Individu::export');
$routes->add('importgpxDisplay', 'ImportGpx::display');
$routes->add('importgpxSelectfile', 'ImportGpx::selectfile');
$routes->add('importgpxExec', 'ImportGpx::exec');
$routes->add('importgpscsvDisplay', 'ImportGpsCsv::display');
$routes->add('importgpscsvExec', 'ImportGpsCsv::exec');
$routes->add('ws1.0testList', 'Test::list');
$routes->add('ws1.0traitsList', 'Traits::list');
$routes->add('ws1.0traitDisplay', 'Traits::display');
$routes->add('manuel_fr', '\Ppci\Controllers\Markdown::doc/fr/manuel_pomet.md');
