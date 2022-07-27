<?php

/**
 * Inheritance: no
 * Variants: no
 *
 * Fields Summary:
 * - ExclusiveCourse [checkbox]
 * - CourseType [select]
 * - CourseID [input]
 * - CourseInstructor [input]
 * - Capacity [numeric]
 * - Duration [numeric]
 * - Level [multiselect]
 * - partnerProfile [manyToOneRelation]
 * - MainCategory [manyToManyObjectRelation]
 * - SecondaryCategories [manyToManyObjectRelation]
 * - CourseImage [image]
 * - localizedfields [localizedfields]
 * -- CourseName [input]
 * -- Description [textarea]
 * -- NeededAccessoires [textarea]
 * - CourseDate [date]
 * - CourseStartTime [time]
 * - Repetition [select]
 * - Weekdays [multiselect]
 * - EndDate [date]
 * - SingleEvents [manyToManyObjectRelation]
 */

return Pimcore\Model\DataObject\ClassDefinition::__set_state(array(
   'id' => 'Course',
   'name' => 'Course',
   'description' => '',
   'creationDate' => 0,
   'modificationDate' => 1655388610,
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
             'name' => 'Layout',
             'type' => NULL,
             'region' => NULL,
             'title' => '',
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
              Pimcore\Model\DataObject\ClassDefinition\Layout\Region::__set_state(array(
                 'fieldtype' => 'region',
                 'name' => 'Basedata',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Basedata',
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
                      Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
                         'fieldtype' => 'checkbox',
                         'defaultValue' => 0,
                         'name' => 'ExclusiveCourse',
                         'title' => 'ExclusiveCourse',
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
                         'visibleGridView' => true,
                         'visibleSearch' => true,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                         'fieldtype' => 'select',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'OnlineCourse',
                            'value' => 'OnlineCourse',
                          ),
                        ),
                         'width' => 260,
                         'defaultValue' => 'OnlineCourse',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'columnLength' => 190,
                         'dynamicOptions' => false,
                         'name' => 'CourseType',
                         'title' => 'CourseType',
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
                      2 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => 260,
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'CourseID',
                         'title' => 'CourseID',
                         'tooltip' => '',
                         'mandatory' => true,
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
                      3 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => 260,
                         'defaultValue' => NULL,
                         'columnLength' => 220,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'CourseInstructor',
                         'title' => 'CourseInstructor',
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
                         'visibleGridView' => true,
                         'visibleSearch' => true,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      4 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
                         'fieldtype' => 'numeric',
                         'width' => 260,
                         'defaultValue' => NULL,
                         'integer' => false,
                         'unsigned' => false,
                         'minValue' => NULL,
                         'maxValue' => NULL,
                         'unique' => false,
                         'decimalSize' => NULL,
                         'decimalPrecision' => NULL,
                         'name' => 'Capacity',
                         'title' => 'Capacity',
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
                      Pimcore\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
                         'fieldtype' => 'numeric',
                         'width' => 100,
                         'defaultValue' => NULL,
                         'integer' => true,
                         'unsigned' => true,
                         'minValue' => NULL,
                         'maxValue' => NULL,
                         'unique' => false,
                         'decimalSize' => NULL,
                         'decimalPrecision' => NULL,
                         'name' => 'Duration',
                         'title' => 'Duration',
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
                      Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                         'fieldtype' => 'multiselect',
                         'options' => 
                        array (
                          0 => 
                          array (
                            'key' => 'Anfänger',
                            'value' => 'Anfänger',
                          ),
                          1 => 
                          array (
                            'key' => 'Fortgeschritten',
                            'value' => 'Fortgeschritten',
                          ),
                        ),
                         'width' => 260,
                         'height' => '',
                         'maxItems' => '',
                         'renderType' => 'tags',
                         'optionsProviderClass' => '',
                         'optionsProviderData' => '',
                         'dynamicOptions' => false,
                         'name' => 'Level',
                         'title' => 'Level',
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
                      7 => 
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
                            'classes' => 'PartnerProfile',
                          ),
                        ),
                         'pathFormatterClass' => '',
                         'name' => 'partnerProfile',
                         'title' => 'partnerProfile',
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
                      8 => 
                      Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                         'fieldtype' => 'panel',
                         'layout' => NULL,
                         'border' => false,
                         'name' => 'Layout',
                         'type' => NULL,
                         'region' => NULL,
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
                          Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation::__set_state(array(
                             'fieldtype' => 'manyToManyObjectRelation',
                             'width' => '',
                             'height' => '',
                             'maxItems' => 1,
                             'relationType' => true,
                             'visibleFields' => 'key,Name',
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
                                'classes' => 'CourseCategory',
                              ),
                            ),
                             'pathFormatterClass' => '',
                             'name' => 'MainCategory',
                             'title' => 'MainCategory',
                             'tooltip' => '',
                             'mandatory' => true,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'invisible' => false,
                             'visibleGridView' => true,
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
                             'maxItems' => '',
                             'relationType' => true,
                             'visibleFields' => 'key,Name',
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
                                'classes' => 'CourseCategory',
                              ),
                            ),
                             'pathFormatterClass' => '',
                             'name' => 'SecondaryCategories',
                             'title' => 'SecondaryCategories',
                             'tooltip' => '',
                             'mandatory' => false,
                             'noteditable' => false,
                             'index' => false,
                             'locked' => false,
                             'style' => '',
                             'permissions' => NULL,
                             'datatype' => 'data',
                             'invisible' => false,
                             'visibleGridView' => true,
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
                         'labelWidth' => 0,
                         'labelAlign' => 'left',
                      )),
                    ),
                     'locked' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'icon' => '',
                     'labelWidth' => 160,
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
                      Pimcore\Model\DataObject\ClassDefinition\Data\Image::__set_state(array(
                         'fieldtype' => 'image',
                         'name' => 'CourseImage',
                         'title' => 'CourseImage',
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
                         'width' => 380,
                         'height' => 280,
                         'uploadPath' => '',
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
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/edit.svg',
              )),
              1 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'fieldtype' => 'panel',
                 'layout' => '',
                 'border' => false,
                 'name' => 'Texts',
                 'type' => NULL,
                 'region' => 'center',
                 'title' => 'Texts',
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
                      Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'fieldtype' => 'input',
                         'width' => 300,
                         'defaultValue' => NULL,
                         'columnLength' => 220,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'name' => 'CourseName',
                         'title' => 'CourseName',
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
                         'visibleGridView' => true,
                         'visibleSearch' => true,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValueGenerator' => '',
                      )),
                      1 => 
                      Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                         'fieldtype' => 'textarea',
                         'width' => 300,
                         'height' => 140,
                         'maxLength' => NULL,
                         'showCharCount' => false,
                         'excludeFromSearchIndex' => false,
                         'name' => 'Description',
                         'title' => 'Description',
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
                      Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                         'fieldtype' => 'textarea',
                         'width' => 300,
                         'height' => 140,
                         'maxLength' => NULL,
                         'showCharCount' => false,
                         'excludeFromSearchIndex' => false,
                         'name' => 'NeededAccessoires',
                         'title' => 'NeededAccessoires',
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
                     'labelWidth' => 180,
                     'labelAlign' => 'left',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/text.svg',
                 'labelWidth' => 160,
                 'labelAlign' => 'left',
              )),
              2 => 
              Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'name' => 'Events',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Events',
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
                  Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                     'fieldtype' => 'panel',
                     'layout' => NULL,
                     'border' => false,
                     'name' => 'Date',
                     'type' => NULL,
                     'region' => NULL,
                     'title' => 'Date',
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
                      Pimcore\Model\DataObject\ClassDefinition\Data\Date::__set_state(array(
                         'fieldtype' => 'date',
                         'queryColumnType' => 'bigint(20)',
                         'columnType' => 'bigint(20)',
                         'defaultValue' => NULL,
                         'useCurrentDate' => true,
                         'name' => 'CourseDate',
                         'title' => 'CourseDate',
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
                      Pimcore\Model\DataObject\ClassDefinition\Data\Time::__set_state(array(
                         'fieldtype' => 'time',
                         'columnLength' => 5,
                         'minValue' => '05:30',
                         'maxValue' => NULL,
                         'increment' => 15,
                         'width' => NULL,
                         'defaultValue' => NULL,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => NULL,
                         'showCharCount' => NULL,
                         'name' => 'CourseStartTime',
                         'title' => 'CourseStartTime',
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
                      2 => 
                      Pimcore\Model\DataObject\ClassDefinition\Layout\Fieldset::__set_state(array(
                         'fieldtype' => 'fieldset',
                         'name' => 'RecurringEvents',
                         'type' => NULL,
                         'region' => NULL,
                         'title' => 'RecurringEvents',
                         'width' => '',
                         'height' => '',
                         'collapsible' => true,
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
                                'key' => 'One-Time',
                                'value' => '0',
                              ),
                              1 => 
                              array (
                                'key' => 'Weekly',
                                'value' => '1',
                              ),
                              2 => 
                              array (
                                'key' => 'Biweekly',
                                'value' => '2',
                              ),
                              3 => 
                              array (
                                'key' => 'Triweekly',
                                'value' => '3',
                              ),
                            ),
                             'width' => '',
                             'defaultValue' => '0',
                             'optionsProviderClass' => '',
                             'optionsProviderData' => '',
                             'columnLength' => 190,
                             'dynamicOptions' => false,
                             'name' => 'Repetition',
                             'title' => 'Repetition',
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
                          Pimcore\Model\DataObject\ClassDefinition\Data\Multiselect::__set_state(array(
                             'fieldtype' => 'multiselect',
                             'options' => 
                            array (
                              0 => 
                              array (
                                'key' => 'Monday',
                                'value' => '1',
                              ),
                              1 => 
                              array (
                                'key' => 'Tuesday',
                                'value' => '2',
                              ),
                              2 => 
                              array (
                                'key' => 'Wednesday',
                                'value' => '3',
                              ),
                              3 => 
                              array (
                                'key' => 'Thursday',
                                'value' => '4',
                              ),
                              4 => 
                              array (
                                'key' => 'Friday',
                                'value' => '5',
                              ),
                              5 => 
                              array (
                                'key' => 'Saturday',
                                'value' => '6',
                              ),
                              6 => 
                              array (
                                'key' => 'Sunday',
                                'value' => '7',
                              ),
                            ),
                             'width' => 300,
                             'height' => '',
                             'maxItems' => '',
                             'renderType' => 'tags',
                             'optionsProviderClass' => '',
                             'optionsProviderData' => '',
                             'dynamicOptions' => false,
                             'name' => 'Weekdays',
                             'title' => 'Weekdays',
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
                          Pimcore\Model\DataObject\ClassDefinition\Data\Date::__set_state(array(
                             'fieldtype' => 'date',
                             'queryColumnType' => 'bigint(20)',
                             'columnType' => 'bigint(20)',
                             'defaultValue' => NULL,
                             'useCurrentDate' => false,
                             'name' => 'EndDate',
                             'title' => 'EndDate',
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
                         'labelWidth' => 100,
                         'labelAlign' => 'left',
                      )),
                      3 => 
                      Pimcore\Model\DataObject\ClassDefinition\Layout\Button::__set_state(array(
                         'fieldtype' => 'button',
                         'handler' => '(function() {
courseEventGenerator.generateCourseEvents(this.object.data.general.o_id);
});',
                         'text' => 'Neue Termine erstellen',
                         'icon' => '',
                         'name' => 'GenerateNewEvents',
                         'type' => NULL,
                         'region' => NULL,
                         'title' => NULL,
                         'width' => NULL,
                         'height' => NULL,
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
                     'labelWidth' => 100,
                     'labelAlign' => 'left',
                  )),
                  1 => 
                  Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation::__set_state(array(
                     'fieldtype' => 'manyToManyObjectRelation',
                     'width' => '',
                     'height' => '',
                     'maxItems' => '',
                     'relationType' => true,
                     'visibleFields' => 'key,published,Weekday,CourseDate,CourseStartTime,Duration,Capacity,Cancelled,Bookings',
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
                        'classes' => 'SingleEvent',
                      ),
                    ),
                     'pathFormatterClass' => '',
                     'name' => 'SingleEvents',
                     'title' => 'SingleEvents',
                     'tooltip' => '',
                     'mandatory' => true,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'invisible' => false,
                     'visibleGridView' => true,
                     'visibleSearch' => true,
                     'blockedVarsForExport' => 
                    array (
                    ),
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/calendar.svg',
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
   'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/side-by-side.svg',
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
      'id' => false,
      'key' => false,
      'path' => false,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => false,
    ),
    'search' => 
    array (
      'id' => false,
      'key' => false,
      'path' => false,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => false,
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
