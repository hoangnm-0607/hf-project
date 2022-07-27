<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\DatedCoursesDto;
use App\Dto\VPP\Courses\CoursesVppAddInputDto;
use App\Dto\VPP\Courses\CoursesVppUpdateInputDto;
use App\Dto\VPP\Courses\CoursesVppOutputDto;
use App\Dto\VPP\Courses\CoursesVppArchiveInputDto;
use App\Dto\VPP\Courses\CoursesListOutputDto;
use Pimcore\Model\DataObject\Course;

/**
 * @ApiResource(
 *  attributes={},
 *  shortName="Courses",
 *  normalizationContext={
 *      "allow_extra_attributes"=false,
 *      "skip_null_values" = false,
 *  },
 *  formats={"json"},
 *  collectionOperations={
 *     "get"={
 *         "output"=DatedCoursesDto::class,
 *         "openapi_context"={
 *             "summary"="Retrieves a filtered collection of published courses and events (Dedicated to app usage)",
 *             "description"="Retrieves a filtered collection of published courses and events (Dedicated to app usage)",
 *             "parameters"={
 *                 {
 *                     "name": "lastUpdateTimestamp",
 *                     "type": "string",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "If a timestamp is provided, the output delivers only the modified objects since ",
 *                     "example": "1523131853",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                  {
 *                     "name": "userId",
 *                     "type": "string",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Requests Courses of a certain user",
 *                     "example": "6ca13d52ca70c883e0f0bb101e42e8624de51db2",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                     "name": "language",
 *                     "type": "string",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Language parameter",
 *                     "example": "de",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *             },
 *
 *         }
 *     },
 *     "add_course" = {
 *          "input"=CoursesVppAddInputDto::class,
 *          "output"=CoursesVppOutputDto::class,
 *          "method"="POST",
 *          "path"="/partners/{publicId}/courses",
 *          "openapi_context"={
 *              "summary"="Adds a course resource for a given partner-id",
 *              "description"="Adds a course resource for a given partner-id",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *              {
 *                     "name": "publicId",
 *                     "type": "string",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Public Id of the related partner",
 *                     "example": "3",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *              },
 *          },
 *     },
 *     "get_collection" = {
 *          "method"="GET",
 *          "path"="/partners/{publicId}/courses",
 *          "output"=CoursesListOutputDto::class,
 *           "openapi_context"={
 *              "summary"="Retrieves the collection of published and unpublished courses (excl. archived) without events for a given partner",
 *              "description"="Retrieves the collection of published and unpublished courses (excl. archived) without events for a given partner",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *                 {
 *                     "name": "publicId",
 *                     "type": "string",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Public Id of the related partner",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "search",
 *                      "description": "Filters the name and internal name by this string",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "open_events",
 *                      "description": "Filters by open events",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean",
 *                          "default"=true,
 *                      }
 *                 },
 *                 {
 *                     "name": "language",
 *                     "type": "string",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Language parameter, used for search filter",
 *                     "example": "de",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *              },
 *          },
 *     },
 *  },
 *  itemOperations={
 *      "update_course"={
 *          "method"="PATCH",
 *          "path"="/partners/{publicId}/courses/{courseId}",
 *          "requirements" = {
 *              "courseId" = "\d+",
 *          },
 *          "input"=CoursesVppUpdateInputDto::class,
 *          "output"=CoursesVppOutputDto::class,
 *          "openapi_context"={
 *              "summary"="Updates a course resource for a given course-id",
 *              "description"="Updates a course resource for a given course-id",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *                 {
 *                     "name": "courseId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the course to update",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "publicId",
 *                     "type": "string",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Public Id of the related partner",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *              },
 *          },
 *
 *      },
 *     "get_course"={
 *          "method"="GET",
 *          "path"="/partners/{publicId}/courses/{courseId}",
 *          "requirements" = {
 *              "courseId" = "\d+",
 *          },
 *          "output"=CoursesVppOutputDto::class,
 *          "openapi_context"={
 *              "summary"="Retrieves a course resource for a given course-id",
 *              "description"="Retrieves a course resource for a given course-id",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *                 {
 *                     "name": "courseId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the course to update",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "publicId",
 *                     "type": "string",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Public Id of the related partner",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *              },
 *          },
 *      },
 *     "archive_course"={
 *          "method"="PUT",
 *          "path"="/partners/{publicId}/courses/{courseId}",
 *          "requirements" = {
 *              "courseId" = "\d+",
 *          },
 *          "input"=CoursesVppArchiveInputDto::class,
 *          "output"=false,
 *          "openapi_context"={
 *              "summary"="Archives the course resource for a given course-id",
 *              "description"="Archives the course resource for a given course-id",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *                 {
 *                     "name": "courseId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the course to update",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "publicId",
 *                     "type": "string",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Public Id of the related partner",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *              },
 *          },
 *      },
 *     "delete_course"={
 *          "method"="DELETE",
 *          "path"="/partners/{publicId}/courses/{courseId}",
 *          "requirements" = {
 *              "courseId" = "\d+",
 *          },
 *          "output"=false,
 *          "openapi_context"={
 *              "summary"="Deletes the given Course (It is not archived)",
 *              "description"="Deletes the given Course (It is not archived)",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *                 {
 *                     "name": "courseId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the course to delete",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "publicId",
 *                     "type": "string",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Public Id of the related partner",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *              },
 *          },
 *      },
 *  }
 * )
 */
final class Courses extends Course
{
    /**
     * @ApiProperty(identifier=true)
     */
 public ?string $courseId = null;
}
