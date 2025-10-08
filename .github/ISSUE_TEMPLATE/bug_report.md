name: Bug report
description: Report a feature of SkyblockCore not working as expected
body:
  - type: markdown
    attributes:
      value: |
        ## Bug description

        > [!TIP]
        > Helpful information to include:
        > - Steps to reproduce the issue
        > - Error backtraces
        > - Crashdumps

        > [!IMPORTANT]
        > **Steps to reproduce are critical to finding the cause of the problem!**
        > Without reproducing steps, the issue will probably not be solvable and may be closed.

  - type: textarea
    attributes:
      label: Problem description
      description: Describe the problem, and how you encountered it
      placeholder: e.g. Steps to reproduce the issue
    validations:
      required: true
  - type: textarea
    attributes:
      label: Expected behaviour
      description: What did you expect to happen?
    validations:
      required: true

  - type: markdown
    attributes:
      value: |
        ## Version, OS and game info
        > [!WARNING]
        > "Latest" is not a valid version.
        > Failure to fill these fields with valid information may result in your issue being closed.

  - type: input
    attributes:
      label: Skyblock version
      placeholder: Use the /about SkyblockCore command in Server
    validations:
      required: true
  - type: input
    attributes:
      label: Server OS
      placeholder: Use the /version command in PocketMine-MP
    validations:
      required: true