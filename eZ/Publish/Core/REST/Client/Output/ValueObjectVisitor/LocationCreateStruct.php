<?php
/**
 * File containing the LocationCreateStruct ValueObjectVisitor class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\REST\Client\Output\ValueObjectVisitor;

use eZ\Publish\Core\REST\Common\Output\ValueObjectVisitor;
use eZ\Publish\Core\REST\Common\Output\Generator;
use eZ\Publish\Core\REST\Common\Output\Visitor;

use eZ\Publish\API\Repository\Values\Content\Location;

/**
 * LocationCreateStruct value object visitor
 */
class LocationCreateStruct extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers
     *
     * @param Visitor $visitor
     * @param Generator $generator
     * @param mixed $data
     * @return void
     */
    public function visit( Visitor $visitor, Generator $generator, $data )
    {
        $generator->startElement( 'LocationCreate' );
        $visitor->setHeader( 'Content-Type', $generator->getMediaType( 'LocationCreate' ) );

        $generator->startElement( 'ParentLocation', 'Location' );
        $generator->startAttribute( 'href', $data->parentLocationId );
        $generator->endAttribute( 'href' );
        $generator->endElement( 'ParentLocation' );

        $generator->startValueElement( 'priority', $data->priority );
        $generator->endValueElement( 'priority' );

        $generator->startValueElement( 'hidden', $data->hidden ? 'true' : 'false' );
        $generator->endValueElement( 'hidden' );

        $generator->startValueElement( 'sortField', $this->getSortFieldName( $data->sortField ) );
        $generator->endValueElement( 'sortField' );

        $generator->startValueElement( 'sortOrder', $data->sortOrder == Location::SORT_ORDER_ASC ? 'ASC' : 'DESC' );
        $generator->endValueElement( 'sortOrder' );

        $generator->endElement( 'LocationCreate' );
    }

    /**
     * Returns the '*' part of SORT_FIELD_* constant name
     *
     * @param int $sortField
     * @return string
     */
    protected function getSortFieldName( $sortField )
    {
        $class = new \ReflectionClass( '\\eZ\\Publish\\API\\Repository\\Values\\Content\\Location' );
        foreach ( $class->getConstants() as $constantName => $constantValue )
        {
            if ( $constantValue == $sortField && strpos( $constantName, 'SORT_FIELD_' ) >= 0 )
            {
                return str_replace( 'SORT_FIELD_', '', $constantName );
            }
        }

        return '';
    }
}
