<?php

namespace App\Exceptions;

/**
 * Class that contains custom constants for exceptions
 */
class Constants{

    const ERROR_CODE = 300;

    const TXT_SUCCESS_CODE = 'Success';
    const TXT_ERROR_CODE = 'Error';
    const TXT_BAD_REQUEST_CODE = 'Bad Request';
    const TXT_RECORD_SAVED = 'Record Saved';
    const TXT_RECORD_UPDATED = 'Record Updated';
    const TXT_RECORD_DELETED = 'Record Deleted';
    const TXT_RECORD_NOT_FOUND_CODE = 'Record Not Found';
    const TXT_RECORD_ALREADY_SAVED = 'Record already saved';
    const TXT_RECORD_DOESNT_SAVED = 'Record does not saved yet';
    const TXT_CANT_EXECUTE_OPERATION = 'Problem during the execution the operation';
    const TXT_INVALID_ARGUMENT_CODE = "Invalid Arguments";
    const TXT_FAILED_DEPENDENCY_CODE = "Failed Dependency";
    const TXT_INTERNAL_SERVER_ERROR_CODE = "Internal Server Error";
    const TXT_INVALID_PAGE_NUMBER = "Invalid Page Number";
    const TXT_INVALID_PAGE_SIZE = "Invalid Page Size";
    const TXT_DEFAULT_LANGUAGE = "The default language can not be updated/deleted";
    const TXT_ID_ALREADY_RELATED_TO = "Can not update serie, it is related to other id";

}