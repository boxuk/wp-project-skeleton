# Keeping up to date

Once you've created a project from the skeleton, the skeleton could still receive updates that you'll be missing out on.

To bring skeleton updates into your project cleanly and easily, you can take the following steps:

1. Add skeleton as a remote

`git remote add skeleton git@github.com:boxuk/wp-project-skeleton.git`

2. Fetch latest from the remote

`git fetch skeleton`

3. Merge skeleton into your project

`git merge skeleton/main --allow-unrelated-histories --squash`

> Notice the `--allow-unrelated-histories` this is the bit that allows us to bring in changes from a unrelated remote.

4. Resolve conflicts

If there are any of course, when we are merging in changes from an unrelated history like this, it won't create a merge bubble, so once you've resolved conflicts, it will act as a fresh commit.

5. Profit!
