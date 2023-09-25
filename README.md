# To create branch in your PC:


### 1. Install git bash

### 2. At htdocs: run git bash
	
### 3. Set config for git bash
```
git config --global user.email "your-email@firstcom.com.sg"
```
### 4. Clone project
```
git clone https://gitlab.com/fcs-programmers-g1/boilerplate2302_v2.git
```

### 5. Change directory into the project folder
```
for e.g. "cd boilerplate2302_v2" 
```
	
### 6. Create branch (CD or not to be advise)
```
git checkout -b branchname
```
	
### 7. Push branch to the GitLab website
```
git push origin branchname
```
	
### 8. So after modification and you would like to push the changes
```
git add .

git commit -m "changes name/details"

git push --set-upstream origin branchname
```	

Merge request has to be submited after changes are made in branch

The file changes will be reflected after the merge request is approved by the owner/master of the files

Then only update to the master file

#### Buena suerte Amigos

