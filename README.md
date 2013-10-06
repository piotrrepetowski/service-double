Service Double <img src="https://travis-ci.org/piotrrepetowski/service-double.png?branch=master" />
===========

Tool that simplifies creation of fake http based services.


Example - fake implementation of JSONRpc service:

1. Define which method should be mocked:

```xml
<?xml version="1.0" encoding="utf-8"?>
<!-- config/config.xml -->
<handlers>
    <handler response="foo_response.xml">
        <matcher type="equals" name="request.method" value="foo" />
    </handler>
    ...
</handlers>
```

Response attributes tell where is stored information about response.
Matcher tells when this response should be returned.

Currently supported matchers:
- any - always true,
- none - always false,
- equals - matches whether specified attribute (see below for more details) has specified value,
- logical and - true when submatchers returns true, false otherwise.

Currently supported attributes:
- request.method - represents http method used in request,
- request.jsonrpc - represents jsonrpc attributes,
- request.get - represents query string attributes.

Request attributes may be nested e.g.:

```json
{
 "result": {
  "name": "foo"
 },
 "errors": null,
 "id": 12
}
```

To use value of name in matcher definition reference to it with result.data.name.

```xml
<matcher type="equals" name="request.jsonrpc.result.name" value="foo" />
```

2. Define proxy to use original service for other calls:

```xml
<?xml version="1.0" encoding="utf-8"?>
<!-- config/config.xml -->
<handlers>
    ...
    <handler url="http://localhost:3500">
        <matcher type="any" />
    </handler>
</handlers>
```

3. Define response

```xml
<?xml version="1.0" encoding="utf-8"?>
<!-- config/foo_response.xml -->
<response>
    <body><![CDATA[FOO]]></body>
</response>
```

This response will return "FOO".

4. Run Service Double server:

```bash
bin/service_double.sh start
```

and use your new service.
