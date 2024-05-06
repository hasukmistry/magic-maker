# Magic Maker

## Table of content
- [Building WordPress Plugin Archive](#building-wordpress-plugin-archive)
    - [Prerequisites](#prerequisites)
    - [Make Commands](#make-commands)
        - [Build](#build)
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

This command generates `wp-dev.zip`, a self-contained archive ready for WordPress installation.

## License

Wp Dev is free software released under the GNU General Public License version 2 or any later version. Refer to [LICENSE](./LICENSE) for details.
