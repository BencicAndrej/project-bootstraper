## Project Bootstraper

Project bootstraper is meant for quickly setting up your Laravel 5 project and generating the basic models, repositories, migrations and so on.

Because this project is still in development, the only way to use the generator is via the config file `generator.xml` in the root of the directory. Once the proper file generation is complete and tested, I intend to provide a simple web interface for creating/uploading the configuration and return a compressed archive of generated files.

##Projects contributed to
* 23.05.2015. - Successfully bootstraped a hackathon project [Link to configuration file](https://github.com/BencicAndrej/project-bootstraper/blob/master/hackathon.xml)

## Example configuration file

```xml
<project name="Coaches" database="coaches">

	<entities>
		<entity name="User" table="users" timestamps="false">
			<guarded name="id" />

            <attribute name="id" type="increments" />
            <attribute name="first_name" type="string" default="John" unique="false" nullable="true" />
            <attribute name="last_name" type="string" default="Doe" unique="false" nullable="false" />

			<relation type="hasMany" name="phoneNumbers" entity="PhoneNumber" foreignKey="user_id" />
			<relation type="hasOne" name="location" entity="Location" foreignKey="user_id" />
			
			<repository name="UserRepository" interface="true" implementation="EloquentUserRepository" decorator="true" />

			<command name="CreateUser" />
		</entity>
		<entity name="PhoneNumber" table="phone_numbers">

            <attribute name="user_id" type="integer" unsigned="true" />
            <attribute name="type" type="integer" default="0" />
            <attribute name="number" type="string" nullable="false" />

			<relation type="belongsTo" name="owner" entity="User" localKey="user_id" />
		</entity>
		<entity name="Location" table="locations">

            <attribute name="user_id" type="integer" unsigned="true" />
            <attribute name="address" type="string" nullable="false" />
            <attribute name="number" type="string" nullable="false" />

			<relation type="belongsTo" name="tenant" entity="User" localKey="user_id" />
		</entity>
	</entities>

	<services>
		<service name="Paypal" />
		<service name="Facebook" />
	</services>

    <packages>
        <package name="barryvdh/ide-helper"
                 version="1.*"
                 provider=""
                 alias=""
                 alias-path="" />
    </packages>

</project>
```
