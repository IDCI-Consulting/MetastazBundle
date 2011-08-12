<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Metastaz\Bundle\MetastazTemplateBundle\Entity\MetastazFieldType,
    Metastaz\Bundle\MetastazTemplateBundle\Entity\ChoiceFieldType,
    Metastaz\Bundle\MetastazTemplateBundle\Entity\DateAndTimeFieldType,
    Metastaz\Bundle\MetastazTemplateBundle\Entity\FieldGroupType,
    Metastaz\Bundle\MetastazTemplateBundle\Entity\HiddenFieldType,
    Metastaz\Bundle\MetastazTemplateBundle\Entity\OtherFieldType,
    Metastaz\Bundle\MetastazTemplateBundle\Entity\TextFieldType;

/**
 * LoadMetastazTemplateData
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class LoadMetastazTemplateData implements FixtureInterface
{
    public function load($em)
    {
        // Choice Fields
        $choice = new ChoiceFieldType();
        $choice->setName('choice');
        $em->persist($choice);
        $em->flush();
        $choice = new ChoiceFieldType();
        $choice->setName('entity');
        $em->persist($choice);
        $em->flush();
        $choice = new ChoiceFieldType();
        $choice->setName('country');
        $em->persist($choice);
        $em->flush();
        $choice = new ChoiceFieldType();
        $choice->setName('language');
        $em->persist($choice);
        $em->flush();
        $choice = new ChoiceFieldType();
        $choice->setName('locale');
        $em->persist($choice);
        $em->flush();
        $choice = new ChoiceFieldType();
        $choice->setName('timezone');
        $em->persist($choice);
        $em->flush();

        // Date and Time Fields¶
        $date = new DateAndTimeFieldType();
        $date->setName('date');
        $em->persist($date);
        $em->flush();
        $date = new DateAndTimeFieldType();
        $date->setName('datetime');
        $em->persist($date);
        $em->flush();
        $date = new DateAndTimeFieldType();
        $date->setName('time');
        $em->persist($date);
        $em->flush();
        $date = new DateAndTimeFieldType();
        $date->setName('birthday');
        $em->persist($date);
        $em->flush();

        // Field Groups
        $group = new FieldGroupType();
        $group->setName('collection');
        $em->persist($group);
        $em->flush();
        $group = new FieldGroupType();
        $group->setName('repeated');
        $em->persist($group);
        $em->flush();

        // Hidden Fields
        $hidden = new HiddenFieldType();
        $hidden->setName('hidden');
        $em->persist($hidden);
        $em->flush();

        // Other Fields
        $other = new OtherFieldType();
        $other->setName('checkbox');
        $em->persist($other);
        $em->flush();
        $other = new OtherFieldType();
        $other->setName('file');
        $em->persist($other);
        $em->flush();
        $other = new OtherFieldType();
        $other->setName('radio');
        $em->persist($other);
        $em->flush();

        // Text Fields
        $text = new TextFieldType();
        $text->setName('text');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('textarea');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('email');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('integer');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('money');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('number');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('password');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('percent');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('search');
        $em->persist($text);
        $em->flush();
        $text = new TextFieldType();
        $text->setName('url');
        $em->persist($text);
        $em->flush();
    }
}

