<?php

// All status
const PAYMENT_STATUS_PENDING = 0;
const PAYMENT_STATUS_PAID = 1;
const PAYMENT_STATUS_CANCELLED = 2;

// All status

const CONVERSATION_TYPE_TEAM = 1;
const CONVERSATION_TYPE_CLIENT = 2;

const WORKING_STATUS_PENDING = 0;
const WORKING_STATUS_PROCESSING = 1;
const WORKING_STATUS_SUCCEED = 2;
const WORKING_STATUS_FAILED = 3;
const WORKING_STATUS_CANCELED = 4;
const WORKING_STATUS_HOLD = 5;

const PAYMENT_STATUS_NOT_INIT = 3;

const STATUS_PENDING = 0;
const STATUS_SUCCESS = 1;
const STATUS_PROCESSING = 2;
const STATUS_REJECT = 3;
const STATUS_REFUNDED = 3;
const STATUS_ACTIVE = 1;
const STATUS_DRAFT = 2;
const STATUS_DISABLE = 3;
const STATUS_DEACTIVATE = 3;
const STATUS_EXPIRED = 4;
const STATUS_SUSPENDED = 5;
const STATUS_CANCELED = 2;

// User Role Type
const USER_STATUS_ACTIVE = 1;
const USER_STATUS_INACTIVE = 0;
const USER_ROLE_ADMIN = 1;
const USER_ROLE_STUDENT = 2;
const USER_ROLE_STAFF = 3;
const USER_ROLE_CONSULTANT = 4;
// Message
const SOMETHING_WENT_WRONG = "Something went wrong! Please try again";
const CREATED_SUCCESSFULLY = "Created Successfully";
const UPDATED_SUCCESSFULLY = "Updated Successfully";
const DELETED_SUCCESSFULLY = "Deleted Successfully";
const UPLOADED_SUCCESSFULLY = "Uploaded Successfully";
const DATA_FETCH_SUCCESSFULLY = "Data Fetch Successfully";
const SENT_SUCCESSFULLY = "Sent Successfully";
const DO_NOT_HAVE_PERMISSION = 7;

// Currency placement
const CURRENCY_SYMBOL_BEFORE=1;

// storage driver
const STORAGE_DRIVER_PUBLIC = 'public';
const STORAGE_DRIVER_AWS = 'aws';
const STORAGE_DRIVER_WASABI = 'wasabi';
const STORAGE_DRIVER_VULTR = 'vultr';
const STORAGE_DRIVER_DO = 'do';

const ACTIVE = 1;
const DEACTIVATE = 0;

const GATEWAY_MODE_LIVE = 1;
const GATEWAY_MODE_SANDBOX = 2;

//Gateway name
const PAYPAL = 'paypal';
const STRIPE = 'stripe';
const RAZORPAY = 'razorpay';
const INSTAMOJO = 'instamojo';
const MOLLIE = 'mollie';
const PAYSTACK = 'paystack';
const SSLCOMMERZ = 'sslcommerz';
const MERCADOPAGO = 'mercadopago';
const FLUTTERWAVE = 'flutterwave';
const BINANCE = 'binance';
const ALIPAY = 'alipay';
const BANK = 'bank';
const CASH = 'cash';
const COINBASE = 'coinbase';
const PAYTM = 'paytm';
const MAXICASH = 'maxicash';
const IYZICO = 'iyzico';
const BITPAY = 'bitpay';
const ZITOPAY = 'zitopay';
const PAYHERE = 'payhere';
const CINETPAY = 'cinetpay';
const VOGUEPAY = 'voguepay';
const TOYYIBPAY = 'toyyibpay';
const PAYMOB = 'paymob';
const AUTHORIZE = 'authorize';
const XENDIT = 'xendit';
const PADDLE = 'paddle';

const DURATION_TYPE_DAY = 1;
const DURATION_TYPE_MONTH = 2;
const DURATION_TYPE_YEAR = 3;
const DEPOSIT_TYPE_BUY = 1;
const DEPOSIT_TYPE_DEPOSIT = 2;

const ORDER_TYPE_DEPOSIT = 1;
const ORDER_TYPE_HARDWARE = 2;
const ORDER_TYPE_PLAN = 3;

const RETURN_TYPE_FIXED = 1;
const RETURN_TYPE_RANDOM = 2;

const EVENT_TYPE_FREE = 1;
const EVENT_TYPE_PAID = 2;

//employee status
const FULL_TIME = 1;
const PART_TIME = 2;
const CONTRACTUAL = 3;
const REMOTE_WORKER = 4;

const DEFAULT_COLOR = 1;
const CUSTOM_COLOR = 2;

const EVENT_TYPE_PHYSICAL = 1;
const EVENT_TYPE_VIRTUAL = 2;

const RESOURCE_TYPE_LOCAL=1;
const RESOURCE_TYPE_YOUTUBE_ID=2;
const RESOURCE_TYPE_PDF=3;
const RESOURCE_TYPE_IMAGE=4;
const RESOURCE_TYPE_AUDIO=5;
const RESOURCE_TYPE_SLIDE=6;

const SCHOLARSHIP_FUNDING_TYPE_PARTIAL = 1;
const SCHOLARSHIP_FUNDING_TYPE_FULL_FUNDED = 2;

const TRANSACTION_EVENT = 1;
const TRANSACTION_COURSE = 2;
const TRANSACTION_CONSULTATION = 3;
const TRANSACTION_SERVICE = 4;
const TRANSACTION_INVOICE = 5;
const TRANSACTION_OTHERS = 6;

const CONSULTATION_TYPE_PHYSICAL = 1;
const CONSULTATION_TYPE_VIRTUAL = 2;

const FUND_TYPE_SELF = 1;
const FUND_TYPE_PARENTS = 2;
const FUND_TYPE_SEEKING_SCHOLARSHIP = 3;
const FUND_TYPE_BANK_LOAN = 4;
const FUND_TYPE_OTHER = 5;
const FUND_TYPE_EMPLOYER_SCHOLARSHIP = 6;

const MEETING_PLATFORMS_PERSON=1;
const MEETING_PLATFORMS_MEET=2;
const MEETING_PLATFORMS_ZOOM=3;

// Email Template Target Audience
const EMAIL_TARGET_AUDIENCE_OF_ADMIN = 1;
const EMAIL_TARGET_AUDIENCE_OF_STUDENT = 2;
const EMAIL_TARGET_AUDIENCE_OF_STAFF = 3;
const EMAIL_TARGET_AUDIENCE_OF_CONSULTER = 4;
const EMAIL_TARGET_AUDIENCE_OF_USER = 5;

const ORDER_TASK_PRIORITY_LOWEST = 5;
const ORDER_TASK_PRIORITY_LOW = 4;
const ORDER_TASK_PRIORITY_MEDIUM = 3;
const ORDER_TASK_PRIORITY_HIGH = 2;
const ORDER_TASK_PRIORITY_HIGHEST = 1;

const ORDER_TASK_STATUS_PENDING = 0;
const ORDER_TASK_STATUS_PROGRESS = 1;
const ORDER_TASK_STATUS_REVIEW = 2;
const ORDER_TASK_STATUS_DONE = 3;
