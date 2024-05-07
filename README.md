# Magic Maker

## Table of content
- [Building WordPress Plugin Archive](#building-wordpress-plugin-archive)
    - [Prerequisites](#prerequisites)
    - [Make Commands](#make-commands)
        - [Build](#build)
- [Shortcodes](#shortcodes)
- [Rest Api](#rest-api)
- [License](#license)

## Building WordPress Plugin Archive

### Prerequisites

Before you begin, ensure you have met the following requirements:

- You have installed Docker and added support for Docker Compose commands. Instructions are available [here](https://docs.docker.com/compose/install/).

- You have installed GNU Make utility, which is commonly used to automate the build process of software projects. Make is often pre-installed if you're using a Unix-like system (such as Linux or macOS). Run this command to verify the installation:
`make --version`

### Make Commands

#### Build

Ensure that `./bin/wp.sh` is executable before using the `make build` command: 

```
chmod +x ./bin/wp.sh
```

To build the WordPress plugin archive, run the following command:

```
make build
```

This command generates `magic-maker.zip`, a self-contained archive ready for WordPress installation.

## Shortcodes

### My Form shortcode 

Adds a form to add new things.

### Examples:

```php
[my_form]
[my_form title="Add Things"]
```

#### Accepted Attributes:

| Attribute | Description | Default |
| --- | --- | --- |
| title | The title of the form. | Magic Maker: Add Things

### My List shortcode

Renders a list of things along with a search form.

### Examples:

```php
[my_list]
[my_list title="All Things"]
[my_list search-form-title="Search Things"]
```

#### Accepted Attributes:

| Attribute | Description | Default |
| --- | --- | --- |
| title | The title of the form. | Magic Maker: All Things
| search-form-title | The title of the search form. | Magic Maker: Search Things

## Rest Api

### My List and Search Rest Apis

Renders available things along with a pagination data.

#### Endpoints

| Endpoint | Description |
| --- | --- |
/things | Get all things
/things/page/1 | Get the first page of things
/things/search/test | Get all things with test in the name
/things/search/test/page/1 | Get the first page of things with test in the name

#### Request

```
curl --location 'https://wordpress.test/wp-json/magic-maker/v1/things/' \
--header 'X-Wp-Nonce: ${nonce}'
```

#### Response

```json
{
    "things": [
        {
            "id": "1",
            "name": "test",
            "created": "2024-05-06 18:44:07"
        },
        {
            "id": "2",
            "name": "testing",
            "created": "2024-05-06 18:44:13"
        },
        {
            "id": "3",
            "name": "test is fun",
            "created": "2024-05-06 18:44:19"
        },
        {
            "id": "4",
            "name": "test is super fun",
            "created": "2024-05-06 18:46:43"
        },
        {
            "id": "5",
            "name": "sample fun",
            "created": "2024-05-06 20:55:23"
        },
        {
            "id": "6",
            "name": "super",
            "created": "2024-05-06 20:56:26"
        },
        {
            "id": "7",
            "name": "another test",
            "created": "2024-05-06 20:59:02"
        },
        {
            "id": "8",
            "name": "another fun",
            "created": "2024-05-06 20:59:07"
        },
        {
            "id": "9",
            "name": "how long is fun.. woow....www.",
            "created": "2024-05-06 21:01:37"
        },
        {
            "id": "10",
            "name": "pagination???",
            "created": "2024-05-06 21:01:52"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total_pages": 3
    }
}
```
### Add a thing Rest Api

Adds a thing.

#### Endpoint

| Endpoint | Description |
| --- | --- |
/things/add | Add a thing

#### Request
```
curl --location 'https://wordpress.test/wp-json/magic-maker/v1/things/add' \
--header 'X-Wp-Nonce: ${nonce}' \
--form 'name="Super Ninja"'
```

#### Response

```json
{
    "thing_id": "11",
    "success": true
}
```
**Note:** The nonce and rest api data is available in the `magicMaker` object via window object. use `magicMaker.rest.nonce` and `magicMaker.rest.url` to get the nonce and the rest api url.

## License

Wp Dev is free software released under the GNU General Public License version 2 or any later version. Refer to [LICENSE](./LICENSE) for details.
