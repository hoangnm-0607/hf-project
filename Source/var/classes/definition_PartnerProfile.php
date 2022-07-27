<?php

/**
 * Inheritance: no
 * Variants: no
 *
 * Fields Summary:
 * - Name [input]
 * - Street [input]
 * - Number [input]
 * - Zip [input]
 * - City [input]
 * - Country [select]
 * - Telephone [input]
 * - email [email]
 * - Website [input]
 * - StudioVisibility [select]
 * - GeoData [geopoint]
 * - PartnerCategoryPrimary [manyToOneRelation]
 * - PartnerCategorySecondary [manyToManyObjectRelation]
 * - HansefitCard [select]
 * - CheckInCard [booleanSelect]
 * - CheckInApp [booleanSelect]
 * - localizedfields [localizedfields]
 * -- CheckInInformation [textarea]
 * -- Holidays [textarea]
 * -- StudioImageTitle [input]
 * -- StudioVideoTitle [input]
 * -- ShortDescription [textarea]
 * -- NotesInformations [textarea]
 * -- Meta1 [input]
 * -- Meta2 [input]
 * -- Meta3 [input]
 * - ServicePackages [manyToManyObjectRelation]
 * - ShowOpeningTimes [select]
 * - OpeningTimes [structuredTable]
 * - FitnessServicesContractInclusive [multiselect]
 * - FitnessServicesContractSurcharge [multiselect]
 * - FitnessServicesInclusive [multiselect]
 * - FitnessServicesSurcharge [multiselect]
 * - WellnessServicesContractInclusive [multiselect]
 * - WellnessServicesContractSurcharge [multiselect]
 * - WellnessServicesInclusive [multiselect]
 * - WellnessServicesSurcharge [multiselect]
 * - ServicesContractInclusive [multiselect]
 * - ServicesContractSurcharge [multiselect]
 * - ServicesInclusive [multiselect]
 * - ServicesSurcharge [multiselect]
 * - Tags [textarea]
 * - assetFolder [manyToOneRelation]
 * - Logo [image]
 * - StudioImage [image]
 * - StudioVideo [video]
 * - Gallery [fieldcollections]
 * - PartnerID [input]
 * - CASPublicID [input]
 * - StartCode [input]
 * - ConfigCheckInApp [input]
 * - StartDate [date]
 * - TerminationDate [date]
 */

return Pimcore\Model\DataObject\ClassDefinition::__set_state(array(
   'id' => 'PartnerProfile',
   'name' => 'PartnerProfile',
   'description' => '',
   'creationDate' => 0,
   'modificationDate' => 1657786438,
   'userOwner' => 2,
   'userModification' => 2,
   'parentClass' => '',
   'implementsInterfaces' => '',
   'listingParentClass' => '',
   'useTraits' => '',
   'listingUseTraits' => '',
   'encryption' => false,
   'encryptedTables' => 
  array (
  ),
   'allowInherit' => false,
   'allowVariants' => false,
   'showVariants' => false,
   'fieldDefinitions' => 
  array (
  ),
   'layoutDefinitions' => 
  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'fieldtype' => 'panel',
     'layout' => NULL,
     'border' => false,
     'name' => 'pimcore_root',
     'type' => NULL,
     'region' => NULL,
     'title' => NULL,
     'width' => 0,
     'height' => 0,
     'collapsible' => false,
     'collapsed' => false,
     'bodyStyle' => NULL,
     'datatype' => 'layout',
     'permissions' => NULL,
     'children' => 
    array (
      0 => 
      Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
         'fieldtype' => 'panel',
         'layout' => NULL,
         'border' => false,
         'name' => 'Layout',
         'type' => NULL,
         'region' => NULL,
         'title' => '',
         'width' => '',
         'height' => '',
         'collapsible' => false,
         'collapsed' => false,
         'bodyStyle' => '',
         'datatype' => 'layout',
         'permissions' => NULL,
         'children' => 
        array (
          0 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Text::__set_state(array(
             'fieldtype' => 'text',
             'html' => '',
             'renderingClass' => 'App\\Service\\DynamicTextService',
             'renderingData' => '',
             'border' => false,
             'name' => 'DynamicText',
             'type' => NULL,
             'region' => NULL,
             'title' => '',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'permissions' => NULL,
             'children' => 
            array (
            ),
             'locked' => false,
             'blockedVarsForExport' => 
            array (
            ),
          )),
          1 => 
          Pimcore\Model\DataObject\ClassDefinition\Layout\Tabpanel::__set_state(array(
             'fieldtype' => 'tabpanel',
             'border' => false,
             'tabPosition' => NULL,
             'name' => 'PartnerProfile',
             'type' => NULL,
             'region' => NULL,
             'title' => '',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'permissions' => NULL,
             'children' => 
            array (
              0 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Region::__set_state(array(
                 'fieldtype' => 'region',
                 'name' => 'BaseData',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'BaseData',
                 'width' => '',
                 'height' => 750,
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'permissions' => NULL,
                 'children' => 
                array (
                  0 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => '',
                     'border' => false,
                     'name' => 'Left',
                     'type' => NULL,
                     'region' => 'west',
                     'title' => '',
                     'width' => 500,
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => NULL,
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'Name',
                         'title' => 'Name',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => NULL,
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'Street',
                         'title' => 'Street',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      2 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => NULL,
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'Number',
                         'title' => 'Number',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      3 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => NULL,
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'Zip',
                         'title' => 'Zip',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      4 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => NULL,
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'City',
                         'title' => 'City',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      5 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                         'fieldtype' => 'select',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Deutschland',
                            'value' => 'DE',
                          ),
                          1 => 
                          array (
                            'key' => 'Niederlande',
                            'value' => 'NL',
                          ),
                          2 => 
                          array (
                            'key' => 'Luxemburg',
                            'value' => 'LU',
                          ),
                        ),
                         'width' => '',
                         'defaultValue' => 'DE',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'columnLength' => 190,
                         'dynamicOptions' => false,
                         'name' => 'Country',
                         'title' => 'Country',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      6 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => '',
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'Telephone',
                         'title' => 'Telephone',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      7 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Email::__set_state(array(
                         'fieldtype' => 'email',
                         'width' => '',
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => NULL,
                         'showCharCount' => NULL,
                         'name' => 'email',
                         'title' => 'E-Mail',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      8 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => '',
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'Website',
                         'title' => 'Website',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 180,
                     'labelAlign' => 'left',
                  )),
                  1 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => '',
                     'border' => false,
                     'name' => 'Center',
                     'type' => NULL,
                     'region' => 'center',
                     'title' => '',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                         'fieldtype' => 'select',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Ja',
                            'value' => 'Ja',
                          ),
                          1 => 
                          array (
                            'key' => 'Nein',
                            'value' => 'Nein',
                          ),
                        ),
                         'width' => '',
                         'defaultValue' => 'Ja',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'columnLength' => 190,
                         'dynamicOptions' => false,
                         'name' => 'StudioVisibility',
                         'title' => 'StudioVisibility',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Geopoint::__set_state(array(
                         'fieldtype' => 'geopoint',
                         'lat' => 0.0,
                         'lng' => 0.0,
                         'zoom' => 1,
                         'width' => NULL,
                         'height' => NULL,
                         'mapType' => 'roadmap',
                         'name' => 'GeoData',
                         'title' => 'GeoData',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      2 => 
                      Pimcore\Model\DataObject\ClassDefinition\Layout\Button::__set_state(array(
                         'fieldtype' => 'button',
                         'handler' => '(function() {
calcGeoDataButton.recalculateGeoData(this.object.data.general.o_id);
});',
                         'text' => 'Recalculate GeoData',
                         'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/refresh.svg',
                         'name' => 'RecalcGeoData',
                         'type' => NULL,
                         'region' => NULL,
                         'title' => NULL,
                         'width' => '',
                         'height' => '',
                         'collapsible' => false,
                         'collapsed' => false,
                         'bodyStyle' => NULL,
                         'datatype' => 'layout',
                         'permissions' => NULL,
                         'children' => 
                        array (
                        ),
                         'locked' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 200,
                     'labelAlign' => 'left',
                  )),
                  2 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => NULL,
                     'border' => false,
                     'name' => 'Bottom',
                     'type' => NULL,
                     'region' => 'south',
                     'title' => 'Categories',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                         'fieldtype' => 'manyToOneRelation',
                         'width' => '',
                         'assetUploadPath' => '',
                         'relationType' => true,
                         'objectsAllowed' => true,
                         'assetsAllowed' => false,
                         'assetTypes' => 
                        array (
                        ),
                         'documentsAllowed' => false,
                         'documentTypes' => 
                        array (
                        ),
                         'classes' => 
                        array (
                          0 => 
                          array (
                            'classes' => 'PartnerCategory',
                          ),
                        ),
                         'pathFormatterClass' => '',
                         'name' => 'PartnerCategoryPrimary',
                         'title' => 'PartnerCategoryPrimary',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation::__set_state(array(
                         'fieldtype' => 'manyToManyObjectRelation',
                         'width' => '',
                         'height' => '',
                         'maxItems' => 6,
                         'relationType' => true,
                         'visibleFields' => 
                        array (
                        ),
                         'allowToCreateNewObject' => false,
                         'optimizedAdminLoading' => false,
                         'enableTextSelection' => false,
                         'visibleFieldDefinitions' => 
                        array (
                        ),
                         'classes' => 
                        array (
                          0 => 
                          array (
                            'classes' => 'PartnerCategory',
                          ),
                        ),
                         'pathFormatterClass' => '',
                         'name' => 'PartnerCategorySecondary',
                         'title' => 'PartnerCategorySecondary',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 200,
                     'labelAlign' => 'left',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/edit.svg',
              )),
              1 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Region::__set_state(array(
                 'fieldtype' => 'region',
                 'name' => 'Information',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Information',
                 'width' => '',
                 'height' => 750,
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'permissions' => NULL,
                 'children' => 
                array (
                  0 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => NULL,
                     'border' => false,
                     'name' => 'Left',
                     'type' => NULL,
                     'region' => 'west',
                     'title' => '',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                         'fieldtype' => 'select',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Ja',
                            'value' => 'Ja',
                          ),
                          1 => 
                          array (
                            'key' => 'Nein',
                            'value' => 'Nein',
                          ),
                        ),
                         'width' => '',
                         'defaultValue' => '',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'columnLength' => 190,
                         'dynamicOptions' => false,
                         'name' => 'HansefitCard',
                         'title' => 'HansefitCard',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\BooleanSelect::__set_state(array(
                         'fieldtype' => 'booleanSelect',
                         'yesLabel' => 'Ja',
                         'noLabel' => 'Nein',
                         'emptyLabel' => '',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => '',
                            'value' => 0,
                          ),
                          1 => 
                          array (
                            'key' => 'Ja',
                            'value' => 1,
                          ),
                          2 => 
                          array (
                            'key' => 'Nein',
                            'value' => -1,
                          ),
                        ),
                         'width' => '',
                         'name' => 'CheckInCard',
                         'title' => 'CheckInCard',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      2 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\BooleanSelect::__set_state(array(
                         'fieldtype' => 'booleanSelect',
                         'yesLabel' => 'Ja',
                         'noLabel' => 'Nein',
                         'emptyLabel' => '',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => '',
                            'value' => 0,
                          ),
                          1 => 
                          array (
                            'key' => 'Ja',
                            'value' => 1,
                          ),
                          2 => 
                          array (
                            'key' => 'Nein',
                            'value' => -1,
                          ),
                        ),
                         'width' => '',
                         'name' => 'CheckInApp',
                         'title' => 'CheckInApp',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      3 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                         'fieldtype' => 'localizedfields',
                         'children' => 
                        array (
                          0 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                             'fieldtype' => 'textarea',
                             'width' => '',
                             'height' => 130,
                             'maxLength' => NULL,
                             'showCharCount' => false,
                             'excludeFromSearchIndex' => false,
                             'name' => 'CheckInInformation',
                             'title' => 'CheckInInformation',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                          )),
                        ),
                         'name' => 'localizedfields',
                         'region' => NULL,
                         'layout' => NULL,
                         'title' => '',
                         'width' => '',
                         'height' => '',
                         'maxTabs' => NULL,
                         'border' => false,
                         'provideSplitView' => false,
                         'tabPosition' => NULL,
                         'hideLabelsWhenTabsReached' => NULL,
                         'referencedFields' => 
                        array (
                          0 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                             'fieldtype' => 'localizedfields',
                             'children' => 
                            array (
                              0 => 
                              Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                                 'fieldtype' => 'textarea',
                                 'width' => 300,
                                 'height' => 130,
                                 'maxLength' => 1000,
                                 'showCharCount' => false,
                                 'excludeFromSearchIndex' => false,
                                 'name' => 'Holidays',
                                 'title' => 'Holidays',
                                 'tooltip' => '',
                                 'mandatory' => false,
                                 'noteditable' => false,
                                 'index' => false,
                                 'locked' => false,
                                 'style' => '',
                                 'permissions' => NULL,
                                 'datatype' => 'data',
                                 'relationType' => false,
                                 'invisible' => false,
                                 'visibleGridView' => false,
                                 'visibleSearch' => false,
                                 'blockedVarsForExport' => 
                                array (
                                ),
                              )),
                            ),
                             'name' => 'localizedfields',
                             'region' => NULL,
                             'layout' => NULL,
                             'title' => '',
                             'width' => '',
                             'height' => '',
                             'maxTabs' => NULL,
                             'border' => false,
                             'provideSplitView' => false,
                             'tabPosition' => NULL,
                             'hideLabelsWhenTabsReached' => NULL,
                             'referencedFields' => 
                            array (
                            ),
                             'fieldDefinitionsCache' => NULL,
                             'permissionView' => NULL,
                             'permissionEdit' => NULL,
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => NULL,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => true,
                             'visibleSearch' => true,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'labelWidth' => 150,
                             'labelAlign' => 'left',
                          )),
                          1 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                             'fieldtype' => 'localizedfields',
                             'children' => 
                            array (
                              0 => 
                              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                                 'fieldtype' => 'input',
                                 'width' => 300,
                                 'defaultValue' => NULL,
                                 'columnLength' => 190,
                                 'regex' => '',
                                 'regexFlags' => 
                                array (
                                ),
                                 'unique' => false,
                                 'showCharCount' => false,
                                 'name' => 'StudioImageTitle',
                                 'title' => 'StudioImageTitle',
                                 'tooltip' => '',
                                 'mandatory' => false,
                                 'noteditable' => false,
                                 'index' => false,
                                 'locked' => false,
                                 'style' => '',
                                 'permissions' => NULL,
                                 'datatype' => 'data',
                                 'relationType' => false,
                                 'invisible' => false,
                                 'visibleGridView' => false,
                                 'visibleSearch' => false,
                                 'blockedVarsForExport' => 
                                array (
                                ),
                                 'defaultValueGenerator' => '',
                              )),
                            ),
                             'name' => 'localizedfields',
                             'region' => NULL,
                             'layout' => NULL,
                             'title' => '',
                             'width' => '',
                             'height' => '',
                             'maxTabs' => NULL,
                             'border' => false,
                             'provideSplitView' => false,
                             'tabPosition' => NULL,
                             'hideLabelsWhenTabsReached' => NULL,
                             'referencedFields' => 
                            array (
                            ),
                             'fieldDefinitionsCache' => NULL,
                             'permissionView' => NULL,
                             'permissionEdit' => NULL,
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => NULL,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => true,
                             'visibleSearch' => true,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'labelWidth' => 0,
                             'labelAlign' => 'left',
                          )),
                          2 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                             'fieldtype' => 'localizedfields',
                             'children' => 
                            array (
                              0 => 
                              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                                 'fieldtype' => 'input',
                                 'width' => 300,
                                 'defaultValue' => NULL,
                                 'columnLength' => 190,
                                 'regex' => '',
                                 'regexFlags' => 
                                array (
                                ),
                                 'unique' => false,
                                 'showCharCount' => false,
                                 'name' => 'StudioVideoTitle',
                                 'title' => 'StudioVideoTitle',
                                 'tooltip' => '',
                                 'mandatory' => false,
                                 'noteditable' => false,
                                 'index' => false,
                                 'locked' => false,
                                 'style' => '',
                                 'permissions' => NULL,
                                 'datatype' => 'data',
                                 'relationType' => false,
                                 'invisible' => false,
                                 'visibleGridView' => false,
                                 'visibleSearch' => false,
                                 'blockedVarsForExport' => 
                                array (
                                ),
                                 'defaultValueGenerator' => '',
                              )),
                            ),
                             'name' => 'localizedfields',
                             'region' => NULL,
                             'layout' => NULL,
                             'title' => '',
                             'width' => '',
                             'height' => '',
                             'maxTabs' => NULL,
                             'border' => false,
                             'provideSplitView' => false,
                             'tabPosition' => NULL,
                             'hideLabelsWhenTabsReached' => NULL,
                             'referencedFields' => 
                            array (
                            ),
                             'fieldDefinitionsCache' => NULL,
                             'permissionView' => NULL,
                             'permissionEdit' => NULL,
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => NULL,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => true,
                             'visibleSearch' => true,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'labelWidth' => 0,
                             'labelAlign' => 'left',
                          )),
                          3 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                             'fieldtype' => 'localizedfields',
                             'children' => 
                            array (
                              0 => 
                              Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                                 'fieldtype' => 'textarea',
                                 'width' => 350,
                                 'height' => 160,
                                 'maxLength' => NULL,
                                 'showCharCount' => false,
                                 'excludeFromSearchIndex' => false,
                                 'name' => 'ShortDescription',
                                 'title' => 'ShortDescription',
                                 'tooltip' => '',
                                 'mandatory' => false,
                                 'noteditable' => false,
                                 'index' => false,
                                 'locked' => false,
                                 'style' => '',
                                 'permissions' => NULL,
                                 'datatype' => 'data',
                                 'relationType' => false,
                                 'invisible' => false,
                                 'visibleGridView' => false,
                                 'visibleSearch' => false,
                                 'blockedVarsForExport' => 
                                array (
                                ),
                              )),
                              1 => 
                              Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                                 'fieldtype' => 'textarea',
                                 'width' => 350,
                                 'height' => 160,
                                 'maxLength' => 1000,
                                 'showCharCount' => true,
                                 'excludeFromSearchIndex' => false,
                                 'name' => 'NotesInformations',
                                 'title' => 'NotesInformations',
                                 'tooltip' => '',
                                 'mandatory' => false,
                                 'noteditable' => false,
                                 'index' => false,
                                 'locked' => false,
                                 'style' => '',
                                 'permissions' => NULL,
                                 'datatype' => 'data',
                                 'relationType' => false,
                                 'invisible' => false,
                                 'visibleGridView' => false,
                                 'visibleSearch' => false,
                                 'blockedVarsForExport' => 
                                array (
                                ),
                              )),
                              2 => 
                              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                                 'fieldtype' => 'input',
                                 'width' => 350,
                                 'defaultValue' => NULL,
                                 'columnLength' => 190,
                                 'regex' => '',
                                 'regexFlags' => 
                                array (
                                ),
                                 'unique' => false,
                                 'showCharCount' => false,
                                 'name' => 'Meta1',
                                 'title' => 'Meta_1',
                                 'tooltip' => '',
                                 'mandatory' => false,
                                 'noteditable' => false,
                                 'index' => false,
                                 'locked' => false,
                                 'style' => '',
                                 'permissions' => NULL,
                                 'datatype' => 'data',
                                 'relationType' => false,
                                 'invisible' => false,
                                 'visibleGridView' => false,
                                 'visibleSearch' => false,
                                 'blockedVarsForExport' => 
                                array (
                                ),
                                 'defaultValueGenerator' => '',
                              )),
                              3 => 
                              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                                 'fieldtype' => 'input',
                                 'width' => 350,
                                 'defaultValue' => NULL,
                                 'columnLength' => 190,
                                 'regex' => '',
                                 'regexFlags' => 
                                array (
                                ),
                                 'unique' => false,
                                 'showCharCount' => false,
                                 'name' => 'Meta2',
                                 'title' => 'Meta_2',
                                 'tooltip' => '',
                                 'mandatory' => false,
                                 'noteditable' => false,
                                 'index' => false,
                                 'locked' => false,
                                 'style' => '',
                                 'permissions' => NULL,
                                 'datatype' => 'data',
                                 'relationType' => false,
                                 'invisible' => false,
                                 'visibleGridView' => false,
                                 'visibleSearch' => false,
                                 'blockedVarsForExport' => 
                                array (
                                ),
                                 'defaultValueGenerator' => '',
                              )),
                              4 => 
                              Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                                 'fieldtype' => 'input',
                                 'width' => 350,
                                 'defaultValue' => NULL,
                                 'columnLength' => 190,
                                 'regex' => '',
                                 'regexFlags' => 
                                array (
                                ),
                                 'unique' => false,
                                 'showCharCount' => false,
                                 'name' => 'Meta3',
                                 'title' => 'Meta_3',
                                 'tooltip' => '',
                                 'mandatory' => false,
                                 'noteditable' => false,
                                 'index' => false,
                                 'locked' => false,
                                 'style' => '',
                                 'permissions' => NULL,
                                 'datatype' => 'data',
                                 'relationType' => false,
                                 'invisible' => false,
                                 'visibleGridView' => false,
                                 'visibleSearch' => false,
                                 'blockedVarsForExport' => 
                                array (
                                ),
                                 'defaultValueGenerator' => '',
                              )),
                            ),
                             'name' => 'localizedfields',
                             'region' => NULL,
                             'layout' => NULL,
                             'title' => '',
                             'width' => '',
                             'height' => '',
                             'maxTabs' => NULL,
                             'border' => false,
                             'provideSplitView' => false,
                             'tabPosition' => NULL,
                             'hideLabelsWhenTabsReached' => NULL,
                             'referencedFields' => 
                            array (
                            ),
                             'fieldDefinitionsCache' => NULL,
                             'permissionView' => NULL,
                             'permissionEdit' => NULL,
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => NULL,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => true,
                             'visibleSearch' => true,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'labelWidth' => 150,
                             'labelAlign' => 'left',
                          )),
                        ),
                         'fieldDefinitionsCache' => NULL,
                         'permissionView' => NULL,
                         'permissionEdit' => NULL,
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => NULL,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => true,
                         'visibleSearch' => true,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'labelWidth' => 190,
                         'labelAlign' => 'left',
                      )),
                      4 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation::__set_state(array(
                         'fieldtype' => 'manyToManyObjectRelation',
                         'width' => 450,
                         'height' => '',
                         'maxItems' => NULL,
                         'relationType' => true,
                         'visibleFields' => 'Name',
                         'allowToCreateNewObject' => false,
                         'optimizedAdminLoading' => false,
                         'enableTextSelection' => false,
                         'visibleFieldDefinitions' => 
                        array (
                        ),
                         'classes' => 
                        array (
                          0 => 
                          array (
                            'classes' => 'ServicePackage',
                          ),
                        ),
                         'pathFormatterClass' => '',
                         'name' => 'ServicePackages',
                         'title' => 'ServicePackages',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 200,
                     'labelAlign' => 'left',
                  )),
                  1 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => NULL,
                     'border' => false,
                     'name' => 'Center',
                     'type' => NULL,
                     'region' => 'center',
                     'title' => '',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                         'fieldtype' => 'select',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Nein',
                            'value' => 'Nein',
                          ),
                          1 => 
                          array (
                            'key' => 'Ja',
                            'value' => 'Ja',
                          ),
                        ),
                         'width' => '',
                         'defaultValue' => 'Nein',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'columnLength' => 190,
                         'dynamicOptions' => false,
                         'name' => 'ShowOpeningTimes',
                         'title' => 'ShowOpeningTimes',
                         'tooltip' => '',
                         'mandatory' => true,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\StructuredTable::__set_state(array(
                         'fieldtype' => 'structuredTable',
                         'width' => '',
                         'height' => '',
                         'labelWidth' => 100,
                         'labelFirstCell' => '',
                         'cols' => 
                        array (
                          0 => 
                          array (
                            'position' => 1,
                            'key' => 'open',
                            'label' => 'Open',
                            'type' => 'bool',
                            'id' => 'extModel6851-1',
                          ),
                          1 => 
                          array (
                            'type' => 'text',
                            'position' => 2,
                            'id' => 'extModel6851-2',
                            'key' => 'time_from1',
                            'label' => 'Time_from1',
                          ),
                          2 => 
                          array (
                            'type' => 'text',
                            'position' => 3,
                            'id' => 'extModel6851-3',
                            'key' => 'time_to1',
                            'label' => 'Time_to1',
                          ),
                          3 => 
                          array (
                            'type' => 'text',
                            'position' => 4,
                            'id' => 'extModel6851-4',
                            'key' => 'time_from2',
                            'label' => 'Time_from2',
                          ),
                          4 => 
                          array (
                            'type' => 'text',
                            'position' => 5,
                            'id' => 'extModel6851-5',
                            'key' => 'time_to2',
                            'label' => 'Time_to2',
                          ),
                          5 => 
                          array (
                            'type' => 'text',
                            'position' => 6,
                            'id' => 'extModel5936-1',
                            'key' => 'time_from3',
                            'label' => 'Time_from3',
                          ),
                          6 => 
                          array (
                            'type' => 'text',
                            'position' => 7,
                            'id' => 'extModel5936-2',
                            'key' => 'time_to3',
                            'label' => 'Time_to3',
                          ),
                        ),
                         'rows' => 
                        array (
                          0 => 
                          array (
                            'position' => 1,
                            'key' => 'monday',
                            'label' => 'Monday',
                            'id' => 'extModel6827-1',
                          ),
                          1 => 
                          array (
                            'position' => 2,
                            'id' => 'extModel11820-1',
                            'key' => 'tuesday',
                            'label' => 'Tuesday',
                          ),
                          2 => 
                          array (
                            'position' => 3,
                            'id' => 'extModel11820-2',
                            'key' => 'wednesday',
                            'label' => 'Wednesday',
                          ),
                          3 => 
                          array (
                            'position' => 4,
                            'id' => 'extModel11820-3',
                            'key' => 'thursday',
                            'label' => 'Thursday',
                          ),
                          4 => 
                          array (
                            'position' => 5,
                            'id' => 'extModel11820-4',
                            'key' => 'friday',
                            'label' => 'Friday',
                          ),
                          5 => 
                          array (
                            'position' => 6,
                            'id' => 'extModel11820-5',
                            'key' => 'saturday',
                            'label' => 'Saturday',
                          ),
                          6 => 
                          array (
                            'position' => 7,
                            'id' => 'extModel11820-6',
                            'key' => 'sunday',
                            'label' => 'Sunday',
                          ),
                        ),
                         'name' => 'OpeningTimes',
                         'title' => 'OpeningTimes',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      2 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                         'fieldtype' => 'localizedfields',
                         'children' => 
                        array (
                          0 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                             'fieldtype' => 'textarea',
                             'width' => 300,
                             'height' => 130,
                             'maxLength' => 1000,
                             'showCharCount' => false,
                             'excludeFromSearchIndex' => false,
                             'name' => 'Holidays',
                             'title' => 'Holidays',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                          )),
                        ),
                         'name' => 'localizedfields',
                         'region' => NULL,
                         'layout' => NULL,
                         'title' => '',
                         'width' => '',
                         'height' => '',
                         'maxTabs' => NULL,
                         'border' => false,
                         'provideSplitView' => false,
                         'tabPosition' => NULL,
                         'hideLabelsWhenTabsReached' => NULL,
                         'referencedFields' => 
                        array (
                        ),
                         'fieldDefinitionsCache' => NULL,
                         'permissionView' => NULL,
                         'permissionEdit' => NULL,
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => NULL,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => true,
                         'visibleSearch' => true,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'labelWidth' => 150,
                         'labelAlign' => 'left',
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 100,
                     'labelAlign' => 'left',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/twemoji/2139.svg',
              )),
              2 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Region::__set_state(array(
                 'fieldtype' => 'region',
                 'name' => 'Services',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Services',
                 'width' => '',
                 'height' => 950,
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'permissions' => NULL,
                 'children' => 
                array (
                  0 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => NULL,
                     'border' => false,
                     'name' => 'Left',
                     'type' => NULL,
                     'region' => 'west',
                     'title' => '',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Layout\Text::__set_state(array(
                         'fieldtype' => 'text',
                         'html' => '<font size="3">​<b style="">Fitnesstraining</b></font>',
                         'renderingClass' => '',
                         'renderingData' => '',
                         'border' => false,
                         'name' => 'Fitnesstraining',
                         'type' => NULL,
                         'region' => NULL,
                         'title' => '',
                         'width' => 200,
                         'height' => 20,
                         'collapsible' => false,
                         'collapsed' => false,
                         'bodyStyle' => '',
                         'datatype' => 'layout',
                         'permissions' => NULL,
                         'children' => 
                        array (
                        ),
                         'locked' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Cardiobereich',
                            'value' => 'Cardiobereich',
                          ),
                          1 => 
                          array (
                            'key' => 'Kraftbereich',
                            'value' => 'Kraftbereich',
                          ),
                          2 => 
                          array (
                            'key' => 'Frauenbereich',
                            'value' => 'Frauenbereich',
                          ),
                          3 => 
                          array (
                            'key' => 'Zirkeltraining',
                            'value' => 'Zirkeltraining',
                          ),
                          4 => 
                          array (
                            'key' => 'Elektronisches Zirkeltraining',
                            'value' => 'Elektronisches Zirkeltraining',
                          ),
                          5 => 
                          array (
                            'key' => 'Vibrationstraining',
                            'value' => 'Vibrationstraining',
                          ),
                          6 => 
                          array (
                            'key' => 'EMS',
                            'value' => 'EMS',
                          ),
                          7 => 
                          array (
                            'key' => 'Kurse',
                            'value' => 'Kurse',
                          ),
                          8 => 
                          array (
                            'key' => 'Präventions- / Rehakurse',
                            'value' => 'Präventions- / Rehakurse',
                          ),
                          9 => 
                          array (
                            'key' => 'Funktionelles Training',
                            'value' => 'Funktionelles Training',
                          ),
                          10 => 
                          array (
                            'key' => 'TRX/-Schlingentraining',
                            'value' => 'TRX/-Schlingentraining',
                          ),
                          11 => 
                          array (
                            'key' => 'Stretch Geräte',
                            'value' => 'Stretch Geräte',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'FitnessServicesContractInclusive',
                         'title' => 'FitnessServicesContractInclusive',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      2 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Cardiobereich',
                            'value' => 'Cardiobereich',
                          ),
                          1 => 
                          array (
                            'key' => 'Kraftbereich',
                            'value' => 'Kraftbereich',
                          ),
                          2 => 
                          array (
                            'key' => 'Frauenbereich',
                            'value' => 'Frauenbereich',
                          ),
                          3 => 
                          array (
                            'key' => 'Zirkeltraining',
                            'value' => 'Zirkeltraining',
                          ),
                          4 => 
                          array (
                            'key' => 'Elektronisches Zirkeltraining',
                            'value' => 'Elektronisches Zirkeltraining',
                          ),
                          5 => 
                          array (
                            'key' => 'Vibrationstraining',
                            'value' => 'Vibrationstraining',
                          ),
                          6 => 
                          array (
                            'key' => 'EMS',
                            'value' => 'EMS',
                          ),
                          7 => 
                          array (
                            'key' => 'Kurse',
                            'value' => 'Kurse',
                          ),
                          8 => 
                          array (
                            'key' => 'Präventions- / Rehakurse',
                            'value' => 'Präventions- / Rehakurse',
                          ),
                          9 => 
                          array (
                            'key' => 'Funktionelles Training',
                            'value' => 'Funktionelles Training',
                          ),
                          10 => 
                          array (
                            'key' => 'TRX/-Schlingentraining',
                            'value' => 'TRX/-Schlingentraining',
                          ),
                          11 => 
                          array (
                            'key' => 'Stretch Geräte',
                            'value' => 'Stretch Geräte',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'FitnessServicesContractSurcharge',
                         'title' => 'FitnessServicesContractSurcharge',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      3 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Cardiobereich',
                            'value' => 'Cardiobereich',
                          ),
                          1 => 
                          array (
                            'key' => 'Kraftbereich',
                            'value' => 'Kraftbereich',
                          ),
                          2 => 
                          array (
                            'key' => 'Frauenbereich',
                            'value' => 'Frauenbereich',
                          ),
                          3 => 
                          array (
                            'key' => 'Zirkeltraining',
                            'value' => 'Zirkeltraining',
                          ),
                          4 => 
                          array (
                            'key' => 'Elektronisches Zirkeltraining',
                            'value' => 'Elektronisches Zirkeltraining',
                          ),
                          5 => 
                          array (
                            'key' => 'Vibrationstraining',
                            'value' => 'Vibrationstraining',
                          ),
                          6 => 
                          array (
                            'key' => 'EMS',
                            'value' => 'EMS',
                          ),
                          7 => 
                          array (
                            'key' => 'Kurse',
                            'value' => 'Kurse',
                          ),
                          8 => 
                          array (
                            'key' => 'Präventions- / Rehakurse',
                            'value' => 'Präventions- / Rehakurse',
                          ),
                          9 => 
                          array (
                            'key' => 'Funktionelles Training',
                            'value' => 'Funktionelles Training',
                          ),
                          10 => 
                          array (
                            'key' => 'TRX/-Schlingentraining',
                            'value' => 'TRX/-Schlingentraining',
                          ),
                          11 => 
                          array (
                            'key' => 'Stretch Geräte',
                            'value' => 'Stretch Geräte',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'FitnessServicesInclusive',
                         'title' => 'FitnessServicesInclusive',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      4 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Cardiobereich',
                            'value' => 'Cardiobereich',
                          ),
                          1 => 
                          array (
                            'key' => 'Kraftbereich',
                            'value' => 'Kraftbereich',
                          ),
                          2 => 
                          array (
                            'key' => 'Frauenbereich',
                            'value' => 'Frauenbereich',
                          ),
                          3 => 
                          array (
                            'key' => 'Zirkeltraining',
                            'value' => 'Zirkeltraining',
                          ),
                          4 => 
                          array (
                            'key' => 'Elektronisches Zirkeltraining',
                            'value' => 'Elektronisches Zirkeltraining',
                          ),
                          5 => 
                          array (
                            'key' => 'Vibrationstraining',
                            'value' => 'Vibrationstraining',
                          ),
                          6 => 
                          array (
                            'key' => 'EMS',
                            'value' => 'EMS',
                          ),
                          7 => 
                          array (
                            'key' => 'Kurse',
                            'value' => 'Kurse',
                          ),
                          8 => 
                          array (
                            'key' => 'Präventions- / Rehakurse',
                            'value' => 'Präventions- / Rehakurse',
                          ),
                          9 => 
                          array (
                            'key' => 'Funktionelles Training',
                            'value' => 'Funktionelles Training',
                          ),
                          10 => 
                          array (
                            'key' => 'TRX/-Schlingentraining',
                            'value' => 'TRX/-Schlingentraining',
                          ),
                          11 => 
                          array (
                            'key' => 'Stretch Geräte',
                            'value' => 'Stretch Geräte',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'FitnessServicesSurcharge',
                         'title' => 'FitnessServicesSurcharge',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      5 => 
                      Pimcore\Model\DataObject\ClassDefinition\Layout\Text::__set_state(array(
                         'fieldtype' => 'text',
                         'html' => '<b style=""><font size="3">Wellness</font></b>',
                         'renderingClass' => '',
                         'renderingData' => '',
                         'border' => false,
                         'name' => 'Wellness',
                         'type' => NULL,
                         'region' => NULL,
                         'title' => '',
                         'width' => 200,
                         'height' => 20,
                         'collapsible' => false,
                         'collapsed' => false,
                         'bodyStyle' => '',
                         'datatype' => 'layout',
                         'permissions' => NULL,
                         'children' => 
                        array (
                        ),
                         'locked' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      6 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Finnische Sauna',
                            'value' => 'Finnische Sauna',
                          ),
                          1 => 
                          array (
                            'key' => 'Kräutersauna',
                            'value' => 'Kräutersauna',
                          ),
                          2 => 
                          array (
                            'key' => 'Damensauna',
                            'value' => 'Damensauna',
                          ),
                          3 => 
                          array (
                            'key' => 'Dampfbad',
                            'value' => 'Dampfbad',
                          ),
                          4 => 
                          array (
                            'key' => 'Hamam',
                            'value' => 'Hamam',
                          ),
                          5 => 
                          array (
                            'key' => 'Infrarotkabine',
                            'value' => 'Infrarotkabine',
                          ),
                          6 => 
                          array (
                            'key' => 'Pool',
                            'value' => 'Pool',
                          ),
                          7 => 
                          array (
                            'key' => 'Whirlpool',
                            'value' => 'Whirlpool',
                          ),
                          8 => 
                          array (
                            'key' => 'Ruheraum',
                            'value' => 'Ruheraum',
                          ),
                          9 => 
                          array (
                            'key' => 'Solarium',
                            'value' => 'Solarium',
                          ),
                          10 => 
                          array (
                            'key' => 'Salzgrotte',
                            'value' => 'Salzgrotte',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'WellnessServicesContractInclusive',
                         'title' => 'WellnessServicesContractInclusive',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      7 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Finnische Sauna',
                            'value' => 'Finnische Sauna',
                          ),
                          1 => 
                          array (
                            'key' => 'Kräutersauna',
                            'value' => 'Kräutersauna',
                          ),
                          2 => 
                          array (
                            'key' => 'Damensauna',
                            'value' => 'Damensauna',
                          ),
                          3 => 
                          array (
                            'key' => 'Dampfbad',
                            'value' => 'Dampfbad',
                          ),
                          4 => 
                          array (
                            'key' => 'Hamam',
                            'value' => 'Hamam',
                          ),
                          5 => 
                          array (
                            'key' => 'Infrarotkabine',
                            'value' => 'Infrarotkabine',
                          ),
                          6 => 
                          array (
                            'key' => 'Pool',
                            'value' => 'Pool',
                          ),
                          7 => 
                          array (
                            'key' => 'Whirlpool',
                            'value' => 'Whirlpool',
                          ),
                          8 => 
                          array (
                            'key' => 'Ruheraum',
                            'value' => 'Ruheraum',
                          ),
                          9 => 
                          array (
                            'key' => 'Solarium',
                            'value' => 'Solarium',
                          ),
                          10 => 
                          array (
                            'key' => 'Salzgrotte',
                            'value' => 'Salzgrotte',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'WellnessServicesContractSurcharge',
                         'title' => 'WellnessServicesContractSurcharge',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      8 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Finnische Sauna',
                            'value' => 'Finnische Sauna',
                          ),
                          1 => 
                          array (
                            'key' => 'Kräutersauna',
                            'value' => 'Kräutersauna',
                          ),
                          2 => 
                          array (
                            'key' => 'Damensauna',
                            'value' => 'Damensauna',
                          ),
                          3 => 
                          array (
                            'key' => 'Dampfbad',
                            'value' => 'Dampfbad',
                          ),
                          4 => 
                          array (
                            'key' => 'Hamam',
                            'value' => 'Hamam',
                          ),
                          5 => 
                          array (
                            'key' => 'Infrarotkabine',
                            'value' => 'Infrarotkabine',
                          ),
                          6 => 
                          array (
                            'key' => 'Pool',
                            'value' => 'Pool',
                          ),
                          7 => 
                          array (
                            'key' => 'Whirlpool',
                            'value' => 'Whirlpool',
                          ),
                          8 => 
                          array (
                            'key' => 'Ruheraum',
                            'value' => 'Ruheraum',
                          ),
                          9 => 
                          array (
                            'key' => 'Solarium',
                            'value' => 'Solarium',
                          ),
                          10 => 
                          array (
                            'key' => 'Salzgrotte',
                            'value' => 'Salzgrotte',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'WellnessServicesInclusive',
                         'title' => 'WellnessServicesInclusive',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      9 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Finnische Sauna',
                            'value' => 'Finnische Sauna',
                            'id' => 'extModel2056-1',
                          ),
                          1 => 
                          array (
                            'key' => 'Kräutersauna',
                            'value' => 'Kräutersauna',
                            'id' => 'extModel2056-2',
                          ),
                          2 => 
                          array (
                            'key' => 'Damensauna',
                            'value' => 'Damensauna',
                            'id' => 'extModel2056-3',
                          ),
                          3 => 
                          array (
                            'key' => 'Dampfbad',
                            'value' => 'Dampfbad',
                            'id' => 'extModel2056-4',
                          ),
                          4 => 
                          array (
                            'key' => 'Hamam',
                            'value' => 'Hamam',
                            'id' => 'extModel2056-5',
                          ),
                          5 => 
                          array (
                            'key' => 'Infrarotkabine',
                            'value' => 'Infrarotkabine',
                            'id' => 'extModel2056-6',
                          ),
                          6 => 
                          array (
                            'key' => 'Pool',
                            'value' => 'Pool',
                            'id' => 'extModel2056-7',
                          ),
                          7 => 
                          array (
                            'key' => 'Whirlpool',
                            'value' => 'Whirlpool',
                            'id' => 'extModel2056-8',
                          ),
                          8 => 
                          array (
                            'key' => 'Ruheraum',
                            'value' => 'Ruheraum',
                            'id' => 'extModel2056-9',
                          ),
                          9 => 
                          array (
                            'key' => 'Solarium',
                            'value' => 'Solarium',
                            'id' => 'extModel2056-10',
                          ),
                          10 => 
                          array (
                            'key' => 'Salzgrotte',
                            'value' => 'Salzgrotte',
                            'id' => 'extModel2056-11',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'WellnessServicesSurcharge',
                         'title' => 'WellnessServicesSurcharge',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      10 => 
                      Pimcore\Model\DataObject\ClassDefinition\Layout\Text::__set_state(array(
                         'fieldtype' => 'text',
                         'html' => '<b style=""><font size="3">Services</font></b>',
                         'renderingClass' => '',
                         'renderingData' => '',
                         'border' => false,
                         'name' => 'Services',
                         'type' => NULL,
                         'region' => NULL,
                         'title' => '',
                         'width' => 200,
                         'height' => 20,
                         'collapsible' => false,
                         'collapsed' => false,
                         'bodyStyle' => '',
                         'datatype' => 'layout',
                         'permissions' => NULL,
                         'children' => 
                        array (
                        ),
                         'locked' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      11 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Personal Training',
                            'value' => 'Personal Training',
                          ),
                          1 => 
                          array (
                            'key' => 'Online-Training',
                            'value' => 'Online-Training',
                          ),
                          2 => 
                          array (
                            'key' => 'Ernährungsberatung',
                            'value' => 'Ernährungsberatung',
                          ),
                          3 => 
                          array (
                            'key' => 'Schließfächer',
                            'value' => 'Schließfächer',
                          ),
                          4 => 
                          array (
                            'key' => 'Materialverleih',
                            'value' => 'Materialverleih',
                          ),
                          5 => 
                          array (
                            'key' => 'Café / Bar / Lounge',
                            'value' => 'Café / Bar / Lounge',
                          ),
                          6 => 
                          array (
                            'key' => 'Getränkestation',
                            'value' => 'Getränkestation',
                          ),
                          7 => 
                          array (
                            'key' => 'Shop',
                            'value' => 'Shop',
                          ),
                          8 => 
                          array (
                            'key' => 'Parkplätze',
                            'value' => 'Parkplätze',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'ServicesContractInclusive',
                         'title' => 'ServicesContractInclusive',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      12 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Personal Training',
                            'value' => 'Personal Training',
                          ),
                          1 => 
                          array (
                            'key' => 'Online-Training',
                            'value' => 'Online-Training',
                          ),
                          2 => 
                          array (
                            'key' => 'Ernährungsberatung',
                            'value' => 'Ernährungsberatung',
                          ),
                          3 => 
                          array (
                            'key' => 'Schließfächer',
                            'value' => 'Schließfächer',
                          ),
                          4 => 
                          array (
                            'key' => 'Materialverleih',
                            'value' => 'Materialverleih',
                          ),
                          5 => 
                          array (
                            'key' => 'Café / Bar / Lounge',
                            'value' => 'Café / Bar / Lounge',
                          ),
                          6 => 
                          array (
                            'key' => 'Getränkestation',
                            'value' => 'Getränkestation',
                          ),
                          7 => 
                          array (
                            'key' => 'Shop',
                            'value' => 'Shop',
                          ),
                          8 => 
                          array (
                            'key' => 'Parkplätze',
                            'value' => 'Parkplätze',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'ServicesContractSurcharge',
                         'title' => 'ServicesContractSurcharge',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      13 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Personal Training',
                            'value' => 'Personal Training',
                            'id' => 'extModel2286-1',
                          ),
                          1 => 
                          array (
                            'key' => 'Online-Training',
                            'value' => 'Online-Training',
                            'id' => 'extModel2286-2',
                          ),
                          2 => 
                          array (
                            'key' => 'Ernährungsberatung',
                            'value' => 'Ernährungsberatung',
                            'id' => 'extModel2286-3',
                          ),
                          3 => 
                          array (
                            'key' => 'Schließfächer',
                            'value' => 'Schließfächer',
                            'id' => 'extModel2286-4',
                          ),
                          4 => 
                          array (
                            'key' => 'Materialverleih',
                            'value' => 'Materialverleih',
                            'id' => 'extModel2286-5',
                          ),
                          5 => 
                          array (
                            'key' => 'Café / Bar / Lounge',
                            'value' => 'Café / Bar / Lounge',
                            'id' => 'extModel2286-6',
                          ),
                          6 => 
                          array (
                            'key' => 'Getränkestation',
                            'value' => 'Getränkestation',
                            'id' => 'extModel2286-7',
                          ),
                          7 => 
                          array (
                            'key' => 'Shop',
                            'value' => 'Shop',
                            'id' => 'extModel2286-8',
                          ),
                          8 => 
                          array (
                            'key' => 'Parkplätze',
                            'value' => 'Parkplätze',
                            'id' => 'extModel2286-9',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'ServicesInclusive',
                         'title' => 'ServicesInclusive',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      14 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Personal Training',
                            'value' => 'Personal Training',
                            'id' => 'extModel2449-1',
                          ),
                          1 => 
                          array (
                            'key' => 'Online-Training',
                            'value' => 'Online-Training',
                            'id' => 'extModel2449-2',
                          ),
                          2 => 
                          array (
                            'key' => 'Ernährungsberatung',
                            'value' => 'Ernährungsberatung',
                            'id' => 'extModel2449-3',
                          ),
                          3 => 
                          array (
                            'key' => 'Schließfächer',
                            'value' => 'Schließfächer',
                            'id' => 'extModel2449-4',
                          ),
                          4 => 
                          array (
                            'key' => 'Materialverleih',
                            'value' => 'Materialverleih',
                            'id' => 'extModel2449-5',
                          ),
                          5 => 
                          array (
                            'key' => 'Café / Bar / Lounge',
                            'value' => 'Café / Bar / Lounge',
                            'id' => 'extModel2449-6',
                          ),
                          6 => 
                          array (
                            'key' => 'Getränkestation',
                            'value' => 'Getränkestation',
                            'id' => 'extModel2449-7',
                          ),
                          7 => 
                          array (
                            'key' => 'Shop',
                            'value' => 'Shop',
                            'id' => 'extModel2449-8',
                          ),
                          8 => 
                          array (
                            'key' => 'Parkplätze',
                            'value' => 'Parkplätze',
                            'id' => 'extModel2449-9',
                          ),
                        ),
                         'width' => '',
                         'height' => '',
                         'maxItems' => NULL,
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'ServicesSurcharge',
                         'title' => 'ServicesSurcharge',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 200,
                     'labelAlign' => 'left',
                  )),
                  1 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => NULL,
                     'border' => false,
                     'name' => 'South',
                     'type' => NULL,
                     'region' => 'west',
                     'title' => '',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                         'fieldtype' => 'textarea',
                         'width' => 300,
                         'height' => 130,
                         'maxLength' => 100,
                         'showCharCount' => true,
                         'excludeFromSearchIndex' => false,
                         'name' => 'Tags',
                         'title' => 'Tags',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 100,
                     'labelAlign' => 'left',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/view_details.svg',
              )),
              3 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Region::__set_state(array(
                 'fieldtype' => 'region',
                 'name' => 'Media',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Media',
                 'width' => '',
                 'height' => 1200,
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'permissions' => NULL,
                 'children' => 
                array (
                  0 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => NULL,
                     'border' => false,
                     'name' => 'Left',
                     'type' => NULL,
                     'region' => 'west',
                     'title' => '',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                         'fieldtype' => 'manyToOneRelation',
                         'width' => 250,
                         'assetUploadPath' => '',
                         'relationType' => true,
                         'objectsAllowed' => false,
                         'assetsAllowed' => true,
                         'assetTypes' => 
                        array (
                          0 => 
                          array (
                            'assetTypes' => 'folder',
                          ),
                        ),
                         'documentsAllowed' => false,
                         'documentTypes' => 
                        array (
                        ),
                         'classes' => 
                        array (
                        ),
                         'pathFormatterClass' => '',
                         'name' => 'assetFolder',
                         'title' => 'assetFolder',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => true,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Image::__set_state(array(
                         'fieldtype' => 'image',
                         'name' => 'Logo',
                         'title' => 'Logo',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'width' => 350,
                         'height' => 200,
                         'uploadPath' => '',
                      )),
                      2 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Image::__set_state(array(
                         'fieldtype' => 'image',
                         'name' => 'StudioImage',
                         'title' => 'StudioImage',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'width' => 350,
                         'height' => 200,
                         'uploadPath' => '',
                      )),
                      3 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                         'fieldtype' => 'localizedfields',
                         'children' => 
                        array (
                          0 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                             'fieldtype' => 'input',
                             'width' => 300,
                             'defaultValue' => NULL,
                             'columnLength' => 190,
                             'regex' => '',
                             'regexFlags' => 
                            array (
                            ),
                             'unique' => false,
                             'showCharCount' => false,
                             'name' => 'StudioImageTitle',
                             'title' => 'StudioImageTitle',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'defaultValueGenerator' => '',
                          )),
                        ),
                         'name' => 'localizedfields',
                         'region' => NULL,
                         'layout' => NULL,
                         'title' => '',
                         'width' => '',
                         'height' => '',
                         'maxTabs' => NULL,
                         'border' => false,
                         'provideSplitView' => false,
                         'tabPosition' => NULL,
                         'hideLabelsWhenTabsReached' => NULL,
                         'referencedFields' => 
                        array (
                        ),
                         'fieldDefinitionsCache' => NULL,
                         'permissionView' => NULL,
                         'permissionEdit' => NULL,
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => NULL,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => true,
                         'visibleSearch' => true,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'labelWidth' => 0,
                         'labelAlign' => 'left',
                      )),
                      4 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Video::__set_state(array(
                         'fieldtype' => 'video',
                         'width' => 400,
                         'height' => '',
                         'name' => 'StudioVideo',
                         'title' => 'StudioVideo',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                      )),
                      5 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                         'fieldtype' => 'localizedfields',
                         'children' => 
                        array (
                          0 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                             'fieldtype' => 'input',
                             'width' => 300,
                             'defaultValue' => NULL,
                             'columnLength' => 190,
                             'regex' => '',
                             'regexFlags' => 
                            array (
                            ),
                             'unique' => false,
                             'showCharCount' => false,
                             'name' => 'StudioVideoTitle',
                             'title' => 'StudioVideoTitle',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'defaultValueGenerator' => '',
                          )),
                        ),
                         'name' => 'localizedfields',
                         'region' => NULL,
                         'layout' => NULL,
                         'title' => '',
                         'width' => '',
                         'height' => '',
                         'maxTabs' => NULL,
                         'border' => false,
                         'provideSplitView' => false,
                         'tabPosition' => NULL,
                         'hideLabelsWhenTabsReached' => NULL,
                         'referencedFields' => 
                        array (
                        ),
                         'fieldDefinitionsCache' => NULL,
                         'permissionView' => NULL,
                         'permissionEdit' => NULL,
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => NULL,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => true,
                         'visibleSearch' => true,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'labelWidth' => 0,
                         'labelAlign' => 'left',
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 100,
                     'labelAlign' => 'left',
                  )),
                  1 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => NULL,
                     'border' => false,
                     'name' => 'Center',
                     'type' => NULL,
                     'region' => 'center',
                     'title' => '',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                      0 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                         'fieldtype' => 'localizedfields',
                         'children' => 
                        array (
                          0 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                             'fieldtype' => 'textarea',
                             'width' => 350,
                             'height' => 160,
                             'maxLength' => NULL,
                             'showCharCount' => false,
                             'excludeFromSearchIndex' => false,
                             'name' => 'ShortDescription',
                             'title' => 'ShortDescription',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                          )),
                          1 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                             'fieldtype' => 'textarea',
                             'width' => 350,
                             'height' => 160,
                             'maxLength' => 1000,
                             'showCharCount' => true,
                             'excludeFromSearchIndex' => false,
                             'name' => 'NotesInformations',
                             'title' => 'NotesInformations',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                          )),
                          2 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                             'fieldtype' => 'input',
                             'width' => 350,
                             'defaultValue' => NULL,
                             'columnLength' => 190,
                             'regex' => '',
                             'regexFlags' => 
                            array (
                            ),
                             'unique' => false,
                             'showCharCount' => false,
                             'name' => 'Meta1',
                             'title' => 'Meta_1',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'defaultValueGenerator' => '',
                          )),
                          3 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                             'fieldtype' => 'input',
                             'width' => 350,
                             'defaultValue' => NULL,
                             'columnLength' => 190,
                             'regex' => '',
                             'regexFlags' => 
                            array (
                            ),
                             'unique' => false,
                             'showCharCount' => false,
                             'name' => 'Meta2',
                             'title' => 'Meta_2',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'defaultValueGenerator' => '',
                          )),
                          4 => 
                          Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                             'fieldtype' => 'input',
                             'width' => 350,
                             'defaultValue' => NULL,
                             'columnLength' => 190,
                             'regex' => '',
                             'regexFlags' => 
                            array (
                            ),
                             'unique' => false,
                             'showCharCount' => false,
                             'name' => 'Meta3',
                             'title' => 'Meta_3',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'relationType' => false,
                             'invisible' => false,
                             'visibleGridView' => false,
                             'visibleSearch' => false,
                             'blockedVarsForExport' => 
                            array (
                            ),
                             'defaultValueGenerator' => '',
                          )),
                        ),
                         'name' => 'localizedfields',
                         'region' => NULL,
                         'layout' => NULL,
                         'title' => '',
                         'width' => '',
                         'height' => '',
                         'maxTabs' => NULL,
                         'border' => false,
                         'provideSplitView' => false,
                         'tabPosition' => NULL,
                         'hideLabelsWhenTabsReached' => NULL,
                         'referencedFields' => 
                        array (
                        ),
                         'fieldDefinitionsCache' => NULL,
                         'permissionView' => NULL,
                         'permissionEdit' => NULL,
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => NULL,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'datatype' => 'data',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => true,
                         'visibleSearch' => true,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'labelWidth' => 150,
                         'labelAlign' => 'left',
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 150,
                     'labelAlign' => 'left',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/asset.svg',
              )),
              4 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'fieldtype' => 'panel',
                 'layout' => '',
                 'border' => false,
                 'name' => 'StudioGallery',
                 'type' => NULL,
                 'region' => '',
                 'title' => 'StudioGallery',
                 'width' => NULL,
                 'height' => NULL,
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'permissions' => NULL,
                 'children' => 
                array (
                  0 => 
                  Pimcore\Model\DataObject\ClassDefinition\Data\Fieldcollections::__set_state(array(
                     'fieldtype' => 'fieldcollections',
                     'allowedTypes' => 
                    array (
                      0 => 'imageDescriptionBlock',
                    ),
                     'lazyLoading' => false,
                     'maxItems' => 10,
                     'disallowAddRemove' => false,
                     'disallowReorder' => false,
                     'collapsed' => false,
                     'collapsible' => false,
                     'border' => false,
                     'name' => 'Gallery',
                     'title' => 'Gallery',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/gallery.svg',
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
              )),
              5 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'name' => 'CASData',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'CAS-Data',
                 'width' => NULL,
                 'height' => NULL,
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'permissions' => NULL,
                 'children' => 
                array (
                  0 => 
                  Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'fieldtype' => 'input',
                     'width' => '',
                     'defaultValue' => NULL,
                     'columnLength' => 190,
                     'regex' => '',
                     'regexFlags' => 
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'name' => 'PartnerID',
                     'title' => 'CASPartnerID',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => true,
                     'index' => true,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValueGenerator' => '',
                  )),
                  1 => 
                  Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'fieldtype' => 'input',
                     'width' => '',
                     'defaultValue' => NULL,
                     'columnLength' => 190,
                     'regex' => '',
                     'regexFlags' => 
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'name' => 'CASPublicID',
                     'title' => 'CASPublicID',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => true,
                     'index' => true,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValueGenerator' => '',
                  )),
                  2 => 
                  Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'fieldtype' => 'input',
                     'width' => '',
                     'defaultValue' => NULL,
                     'columnLength' => 190,
                     'regex' => '^[a-zA-z\\d]{12}$',
                     'regexFlags' => 
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'name' => 'StartCode',
                     'title' => 'StartCode',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => true,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValueGenerator' => '',
                  )),
                  3 => 
                  Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'fieldtype' => 'input',
                     'width' => '',
                     'defaultValue' => NULL,
                     'columnLength' => 190,
                     'regex' => '',
                     'regexFlags' => 
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'name' => 'ConfigCheckInApp',
                     'title' => 'ConfigCheckInApp',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => true,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValueGenerator' => '',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/collect.svg',
                 'labelWidth' => 200,
                 'labelAlign' => 'left',
              )),
              6 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'name' => 'Laufzeit',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Laufzeit',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'permissions' => NULL,
                 'children' => 
                array (
                  0 => 
                  Pimcore\Model\DataObject\ClassDefinition\Data\Date::__set_state(array(
                     'fieldtype' => 'date',
                     'queryColumnType' => 'bigint(20)',
                     'columnType' => 'bigint(20)',
                     'defaultValue' => NULL,
                     'useCurrentDate' => false,
                     'name' => 'StartDate',
                     'title' => 'StartDate',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValueGenerator' => '',
                  )),
                  1 => 
                  Pimcore\Model\DataObject\ClassDefinition\Data\Date::__set_state(array(
                     'fieldtype' => 'date',
                     'queryColumnType' => 'bigint(20)',
                     'columnType' => 'bigint(20)',
                     'defaultValue' => NULL,
                     'useCurrentDate' => false,
                     'name' => 'TerminationDate',
                     'title' => 'TerminationDate',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValueGenerator' => '',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/planner.svg',
                 'labelWidth' => 0,
                 'labelAlign' => 'left',
              )),
              7 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'name' => 'Users',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Users',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'permissions' => NULL,
                 'children' => 
                array (
                  0 => 
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Text::__set_state(array(
                     'fieldtype' => 'text',
                     'html' => '',
                     'renderingClass' => '\\App\\Service\\CognitoUsersTextService',
                     'renderingData' => '',
                     'border' => false,
                     'name' => 'CognitoUsers',
                     'type' => NULL,
                     'region' => NULL,
                     'title' => '',
                     'width' => '',
                     'height' => '',
                     'collapsible' => false,
                     'collapsed' => false,
                     'bodyStyle' => '',
                     'datatype' => 'layout',
                     'permissions' => NULL,
                     'children' => 
                    array (
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/businesswoman.svg',
                 'labelWidth' => 200,
                 'labelAlign' => 'left',
              )),
            ),
             'locked' => false,
             'blockedVarsForExport' => 
            array (
            ),
          )),
        ),
         'locked' => false,
         'blockedVarsForExport' => 
        array (
        ),
         'icon' => '',
         'labelWidth' => 0,
         'labelAlign' => 'left',
      )),
    ),
     'locked' => false,
     'blockedVarsForExport' => 
    array (
    ),
     'icon' => NULL,
     'labelWidth' => 100,
     'labelAlign' => 'left',
  )),
   'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/wysiwyg.svg',
   'previewUrl' => '',
   'group' => '',
   'showAppLoggerTab' => false,
   'linkGeneratorReference' => '',
   'previewGeneratorReference' => '',
   'compositeIndices' => 
  array (
  ),
   'generateTypeDeclarations' => false,
   'showFieldLookup' => false,
   'propertyVisibility' => 
  array (
    'grid' => 
    array (
      'id' => true,
      'key' => false,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
    'search' => 
    array (
      'id' => true,
      'key' => false,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
  ),
   'enableGridLocking' => false,
   'deletedDataComponents' => 
  array (
  ),
   'dao' => NULL,
   'blockedVarsForExport' => 
  array (
  ),
   'activeDispatchingEvents' => 
  array (
  ),
));
