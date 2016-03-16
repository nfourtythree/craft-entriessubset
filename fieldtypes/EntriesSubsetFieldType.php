<?php
/**
 * Entries Subset plugin for Craft CMS
 *
 * EntriesSubset FieldType
 *
 * @author    nfourtythree
 * @copyright Copyright (c) 2016 nfourtythree
 * @link      http://n43.me
 * @package   EntriesSubset
 * @since     0.5.0
 */

namespace Craft;

class EntriesSubsetFieldType extends BaseElementFieldType
{

    protected $elementType = 'Entry';
    /**
     * Returns the name of the fieldtype.
     *
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('EntriesSubset');
    }

    public function defineSettings()
    {
        $settings = parent::defineSettings();

        $settings['entryTypes'] = array(AttributeType::Mixed, 'default' => '');

        return $settings;
    }


    public function getSettingsHtml()
    {
        $settingsHtml = parent::getSettingsHtml();

        return $settingsHtml . craft()->templates->render('entriessubset/fields/settings', array(
            'settings' => $this->getSettings(),
            'entryTypes' => $this->getEntryTypeOptions(),
            'type' => $this->getName(),
        ));;

    }

    public function getEntryTypeOptions()
    {
        $sectionIds = craft()->sections->getAllSectionIds();
        $entryTypes = array();
        foreach ($sectionIds as $id) {
            $entryTypes = array_merge($entryTypes, craft()->sections->getEntryTypesBySectionId($id));
        }

        $entryTypeOptions = array(array('label' => Craft::t('All Entry Types'), 'value' => '*'));
        foreach ($entryTypes as $type) {
            $entryTypeOptions[] = array('label' => $type->name, 'value' => $type->id);
        }

        return $entryTypeOptions;
    }

    public function getInputTemplateVariables($name, $criteria)
    {
        $variables = parent::getInputTemplateVariables($name, $criteria);

        $entryTypes = $this->getSettings()->entryTypes;

        if ($entryTypes and is_array($entryTypes)) {
            foreach($entryTypes as $typeId) {
                if ($typeId != "*") {
                    $entryType = craft()->sections->getEntryTypeById($typeId);
                    $variables['criteria']['type'][] = $entryType->handle;
                }
            }
        }

        return $variables;
    }
}